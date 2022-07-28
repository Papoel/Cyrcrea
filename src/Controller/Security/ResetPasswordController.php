<?php

namespace App\Controller\Security;

use App\Entity\User;
use App\Form\ChangePasswordFormType;
use App\Form\ResetPasswordRequestFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;

#[Route('/reset-password')]
class ResetPasswordController extends AbstractController
{
    use ResetPasswordControllerTrait;

    public function __construct(
        private ResetPasswordHelperInterface $resetPasswordHelper,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Afficher et traiter le formulaire de demande de réinitialisation du mot de passe.
     */
    #[Route('', name: 'app_forgot_password_request')]
    public function request(Request $request, MailerInterface $mailer, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(ResetPasswordRequestFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->processSendingPasswordResetEmail(
                $form->get('email')->getData(),
                $mailer,
                $translator
            );
        }

        return $this->render('reset_password/request.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    /**
     * Page de confirmation après qu'un utilisateur a demandé une réinitialisation de son mot de passe.
     */
    #[Route('/check-email', name: 'app_check_email')]
    public function checkEmail(): Response
    {
        // Génère un faux jeton si l'utilisateur n'existe pas ou si quelqu'un a accédé directement à cette page.
        // Cela évite d'exposer si un utilisateur a été trouvé avec l'adresse e-mail donnée ou non.
        if (null === ($resetToken = $this->getTokenObjectFromSession())) {
            $resetToken = $this->resetPasswordHelper->generateFakeResetToken();
        }

        return $this->render('reset_password/check_email.html.twig', [
            'resetToken' => $resetToken,
        ]);
    }

    /**
     * Valide et traite l'URL de réinitialisation sur laquelle l'utilisateur a cliqué dans son courriel.
     */
    #[Route('/reset/{token}', name: 'app_reset_password')]
    public function reset(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        TranslatorInterface $translator,
        string $token = null
    ): Response {
        if ($token) {
            // Nous stockons le jeton en session et le supprimons de l'URL, pour éviter que l'URL ne soit
            // chargée dans un navigateur et la fuite potentielle du jeton vers un JavaScript tiers.
            $this->storeTokenInSession($token);

            return $this->redirectToRoute('app_reset_password');
        }

        $token = $this->getTokenFromSession();
        if (null === $token) {
            throw $this->createNotFoundException(
                message: "Aucun jeton de réinitialisation du mot de passe n'a été trouvé dans l'URL ou dans la session."
            );
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash(
                'reset_password_error',
                message: sprintf(
                    '%s - %s',
                    $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_VALIDATE, [], 'ResetPasswordBundle'),
                    $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
                )
            );

            return $this->redirectToRoute('app_forgot_password_request');
        }

        // Le jeton est valide ; autorisez l'utilisateur à changer son mot de passe.
        $form = $this->createForm(ChangePasswordFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Un jeton de réinitialisation de mot de passe ne doit être utilisé qu'une seule fois, retirez-le.
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encoder(hash) le mot de passe simple, et le définir.
            /** @var User $user */
            $plainPassword = $form->get('plainPassword')->getData();
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPlainPassword($plainPassword);
            $user->setPassword($encodedPassword);


            $this->entityManager->flush();

            // La session est nettoyée après que le mot de passe ait été changé.
            $this->cleanSessionAfterReset();

            return $this->redirectToRoute('app_compte_edit');
        }

        return $this->render('reset_password/reset.html.twig', [
            'resetForm' => $form->createView(),
        ]);
    }

    // TODO: #1 => Pourquoi le Token n'est pas affiché ?...
    private function processSendingPasswordResetEmail(string $emailFormData, MailerInterface $mailer, TranslatorInterface $translator): RedirectResponse
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy([
            'email' => $emailFormData,
        ]);

        // Ne pas révéler si un compte utilisateur a été trouvé ou non.
        if (!$user) {
            return $this->redirectToRoute('app_check_email');
        }

        try {
            $resetToken = $this->resetPasswordHelper->generateResetToken($user);
        } catch (ResetPasswordExceptionInterface $e) {
            // Si vous voulez dire à l'utilisateur pourquoi un email de réinitialisation n'a pas été envoyé, dé commentez
            // les lignes ci-dessous et changez la redirection en 'app_forgot_password_request'.
            // Attention : Cela peut révéler si un utilisateur est enregistré ou non.
            //
            //$this->addFlash('reset_password_error', sprintf(
            //    '%s - %s',
            //    $translator->trans(ResetPasswordExceptionInterface::MESSAGE_PROBLEM_HANDLE, [], 'ResetPasswordBundle'),
            //    $translator->trans($e->getReason(), [], 'ResetPasswordBundle')
            //));
            return $this->redirectToRoute('app_check_email');
        }

        $email = (new TemplatedEmail())
            ->from(new Address('contact@cyrcrea.com', '"La Boutique CyrCrea"'))
            ->to($user->getEmail())
            ->subject('Votre demande de réinitialisation de mot de passe')
            ->htmlTemplate('reset_password/email.html.twig')
            ->context([
                'resetToken' => $resetToken,
            ])
        ;

        // dd("Dump de l'Email", $email->getContext()['resetToken']);
        try {
            $mailer->send($email);
            $this->addFlash('success', 'Un email de réinitialisation de mot de passe a été envoyé à votre adresse email.');
        } catch (TransportExceptionInterface $e) {
            $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de l\'email de réinitialisation de mot de passe.');
        }

         $this->setTokenObjectInSession($resetToken);


        return $this->redirectToRoute('app_check_email');
    }
}

    // TODO: Lorsque le mail est généré j'ai une 404 ...
    // TODO: Je dois régler le problème de la redirection vers la route reset/{token}
