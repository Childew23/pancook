<?php

namespace App\Tests\Functional;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AddArticleTest extends WebTestCase
{
    public function testUserCanAddArticle(): void
    {
        $client = static::createClient();

        $container = self::getContainer();
        $entityManager = $container->get('doctrine')->getManager();
        $passwordHasher = $container->get('security.user_password_hasher');
        $testImagePath = __DIR__ . '/test_image.jpg';
        copy(__DIR__ . '/fixtures/test_image.jpg', $testImagePath); // si besoin de copier depuis un dossier fixtures

        $uploadedFile = new UploadedFile(
            $testImagePath,
            'test_image.jpg',
            'image/jpeg',
            null,
            true // true = test mode, le fichier n'est pas vraiment uploadé
        );

        // Nettoyage utilisateur
        $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => 'user@example.com']);
        if ($existingUser) {
            $entityManager->remove($existingUser);
            $entityManager->flush();
        }

        // Création utilisateur
        $user = new User();
        $user->setEmail('user@example.com');
        $user->setRoles(['ROLE_USER']);
        $user->setUsername('Testeur');
        $user->setPassword(
            $passwordHasher->hashPassword($user, 'passwordTest')
        );
        $entityManager->persist($user);
        $entityManager->flush();

        // Création catégorie
        $category = new Category();
        $category->setName('Test');
        $entityManager->persist($category);
        $entityManager->flush();

        // Connexion utilisateur
        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Connexion')->form([
            'email' => 'user@example.com',
            'password' => 'passwordTest'
        ]);
        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('a[href="/logout"]');

        // Accès à la page d'ajout d'article
        $crawler = $client->request('GET', '/post/add');

        // Soumission du formulaire d'ajout d'article
        $form = $crawler->selectButton('Valider')->form([
            'post[title]' => 'Mon article de test',
            'post[content]' => 'Ceci est le contenu de mon article de test.',
            'post[category]' => $category->getId(),
        ]);

        $client->submit($form, [
            'post[imageFile]' => $uploadedFile
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
    }
}
