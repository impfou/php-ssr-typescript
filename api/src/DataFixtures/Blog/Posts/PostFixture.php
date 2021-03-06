<?php

declare(strict_types=1);

namespace App\DataFixtures\Blog\Posts;

use App\DataFixtures\Blog\Author\AuthorFixture;
use App\DataFixtures\Blog\Categories\CategoryFixture;
use App\Model\Blog\Entity\Author\Author;
use App\Model\Blog\Entity\Categories\Category\Category;
use App\Model\Blog\Entity\Posts\Post\Id;
use App\Model\Blog\Entity\Posts\Post\Logo\Id as LogoId;
use App\Model\Blog\Entity\Posts\Post\Logo\Info as LogoInfo;
use App\Model\Blog\Entity\Posts\Post\Logo\Logo;
use App\Model\Blog\Entity\Posts\Post\Meta;
use App\Model\Blog\Entity\Posts\Post\Post;
use App\Model\Blog\Entity\Posts\Post\Seo;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostFixture extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_NEWS_CIANAO_1 = 'blog_news_cianao_1';
    public const REFERENCE_NEWS_CIANAO_2 = 'blog_news_cianao_2';
    public const REFERENCE_NEWS_CIANAO_3 = 'blog_news_cianao_3';
    public const REFERENCE_HELP_PAYMENT = 'blog_help_payment';

    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager): void
    {
        foreach ($this->getPostsData() as $data) {
            $post = $this->createPost(
                $data['author'],
                $data['category'],
                $data['name'],
                $data['metaTitle'],
                $data['metaDescription'],
                $data['sort'],
                $data['description'],
                $data['logoName'],
            );
            $manager->persist($post);
            $this->setReference($data['ref'], $post);
        }

        $manager->flush();
    }

    private function createPost(
        Author $author,
        Category $category,
        string $name,
        string $metaTitle,
        string $metaDescription,
        int $sort,
        string $description,
        string $logoName,
    ): Post {
        $post = new Post(
            Id::next(),
            $author,
            $name,
            new DateTimeImmutable(),
            $this->slugger->slug($name)->lower()->toString(),
            new Seo(),
            new Meta($metaTitle, $metaDescription),
            $sort,
            $description
        );

        $post->setLogo(
            new Logo(
                LogoId::next(),
                $post,
                new LogoInfo(
                    'fixtures/blog',
                    $logoName,
                    1
                ),
                new DateTimeImmutable(),
            )
        );

        $post->addCategory($category);
        $category->addPost($post);

        return $post;
    }

    public function getDependencies(): array
    {
        return [
            AuthorFixture::class,
            CategoryFixture::class,
        ];
    }

    private function getPostsData(): array
    {
        /** @var Author $author */
        $author = $this->getReference(AuthorFixture::REFERENCE_ADMIN);
        /** @var Category $newsCategory */
        $newsCategory = $this->getReference(CategoryFixture::REFERENCE_NEWS);
        /** @var Category $helpCategory */
        $helpCategory = $this->getReference(CategoryFixture::REFERENCE_HELP);

        return [
            [
                'name' => '?????????????????? Cianao ?? ???????????? 1',
                'category' => $newsCategory,
                'author' => $author,
                'ref' => self::REFERENCE_NEWS_CIANAO_1,
                'description' => '<p>???????????????????? ???????????? ?? AliExpress ?????????? ?????? ??????????. ?? ?????????? 2020 ???????? ?????????????????????????? ???????????????? Cainiao ?????????????????? ???????????? ?? ???????????? ????????????-???????????? &mdash; ?????????????????????? ???????? ???????????????????? ?????? ???????????????? ?????????????? ?? AliExpress. ???????????? ???????????????????????? ???????? ???????????????????? ?? ???????????????????? ?????? ???????????????? 11 ????????????.</p>

<p>???????????? ???????????????? ???????????? ?? ???????????????????????? ??????????????, ?????? ???????????????????? ???????????? ???????????? ????????????????. ???????????????????????? ???? ?????????????????????????????? ???????????????? ?????????????????????? ???????? &laquo;Cainiao&raquo; ?? ???????????? - ???????????? ???????????????? ????????????????, ?????? ???????????????? ?????????????????? ?????????????? ?????????? 1&nbsp;000 ?????????????? ???????????? ???? ???????? ????????????. ????????????, ???? ???????????? ????????????, ?????????????????? ???????????? ?????????????????????? ???????????? ?? ???????????? ?? ???????????????????? ??????????????.</p>

<p>?????????? ???????? ?????????? ???????????????? ?????????????? ???????????????? ?????????????? ?????????????????????????? ?????? ?????????????????? ?????????????? ?? AliExpress. ?????? ?????????? ???????????????????????????? ?????????????????? ?????????????????? ?????? ?????????????????? ?? ?????????? ?????????????????????? ???????????????? ?????? ??????????????????????.</p>

<h2><strong>?????????????????? Cainiao ?? ????????????</strong></h2>


<p>????????????????, ???????? ???? ?????????????? ???????????????? ?????? ?????? ?????????????????????? ?????????????????? ???????????? ????????????:</p>

<ol>
	<li>???????????????? ??????????, 17 ??4. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 23:00</li>
	<li>???????? ????????????????, 95 ????2. ???????????? ????????????: ??????????????????????????</li>
	<li>??????????????????????, 40/17. ???????????? ????????????: ?????????????????? ?? 09:00 ???? 22:00</li>
	<li>???????????????? 3-??, 53. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 23:00</li>
	<li>???? ??????????????????????????. ???????????? ????????????: ?????????????????? ?? 10:00 ???? 21:00</li>
	<li>???????????????????????? 2-??, 32 ??1</li>
	<li>?????????????????? ??????????, 17 ??2. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 22:00</li>
	<li>?????????????? ??????????????????, 30 ????1. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 21:00</li>
	<li>????????????????????, 15. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 23:00</li>
	<li>????????????????????, 36. ???????????? ????????????: ?????????????????? ?? 09:00 ???? 21:00</li>
</ol>',
                'sort' => 0,
                'metaTitle' => '?????????????????? Cianao ??? ???????????? ???????????????? ???? ??????????',
                'metaDescription' => '???????????? ???????????? ?????????????? ???????????????????? Cianao ?? ????????????. ?????????? ???? ???????????? ?????????? ???????????? ???????? ???????????????????? ???????????? ???? ????????????, ?????? ?? ???????????? ?????????????? ????????????.',
                'logoName' => 'cianao.jpg',
            ],
            [
                'name' => '?????????????????? Cianao ?? ???????????? 2',
                'category' => $newsCategory,
                'author' => $author,
                'ref' => self::REFERENCE_NEWS_CIANAO_2,
                'description' => '<p>???????????????????? ???????????? ?? AliExpress ?????????? ?????? ??????????. ?? ?????????? 2020 ???????? ?????????????????????????? ???????????????? Cainiao ?????????????????? ???????????? ?? ???????????? ????????????-???????????? &mdash; ?????????????????????? ???????? ???????????????????? ?????? ???????????????? ?????????????? ?? AliExpress. ???????????? ???????????????????????? ???????? ???????????????????? ?? ???????????????????? ?????? ???????????????? 11 ????????????.</p>

<p>???????????? ???????????????? ???????????? ?? ???????????????????????? ??????????????, ?????? ???????????????????? ???????????? ???????????? ????????????????. ???????????????????????? ???? ?????????????????????????????? ???????????????? ?????????????????????? ???????? &laquo;Cainiao&raquo; ?? ???????????? - ???????????? ???????????????? ????????????????, ?????? ???????????????? ?????????????????? ?????????????? ?????????? 1&nbsp;000 ?????????????? ???????????? ???? ???????? ????????????. ????????????, ???? ???????????? ????????????, ?????????????????? ???????????? ?????????????????????? ???????????? ?? ???????????? ?? ???????????????????? ??????????????.</p>

<p>?????????? ???????? ?????????? ???????????????? ?????????????? ???????????????? ?????????????? ?????????????????????????? ?????? ?????????????????? ?????????????? ?? AliExpress. ?????? ?????????? ???????????????????????????? ?????????????????? ?????????????????? ?????? ?????????????????? ?? ?????????? ?????????????????????? ???????????????? ?????? ??????????????????????.</p>

<h2><strong>?????????????????? Cainiao ?? ????????????</strong></h2>


<p>????????????????, ???????? ???? ?????????????? ???????????????? ?????? ?????? ?????????????????????? ?????????????????? ???????????? ????????????:</p>

<ol>
	<li>???????????????? ??????????, 17 ??4. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 23:00</li>
	<li>???????? ????????????????, 95 ????2. ???????????? ????????????: ??????????????????????????</li>
	<li>??????????????????????, 40/17. ???????????? ????????????: ?????????????????? ?? 09:00 ???? 22:00</li>
	<li>???????????????? 3-??, 53. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 23:00</li>
	<li>???? ??????????????????????????. ???????????? ????????????: ?????????????????? ?? 10:00 ???? 21:00</li>
	<li>???????????????????????? 2-??, 32 ??1</li>
	<li>?????????????????? ??????????, 17 ??2. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 22:00</li>
	<li>?????????????? ??????????????????, 30 ????1. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 21:00</li>
	<li>????????????????????, 15. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 23:00</li>
	<li>????????????????????, 36. ???????????? ????????????: ?????????????????? ?? 09:00 ???? 21:00</li>
</ol>',
                'sort' => 0,
                'metaTitle' => '?????????????????? Cianao ??? ???????????? ???????????????? ???? ??????????',
                'metaDescription' => '???????????? ???????????? ?????????????? ???????????????????? Cianao ?? ????????????. ?????????? ???? ???????????? ?????????? ???????????? ???????? ???????????????????? ???????????? ???? ????????????, ?????? ?? ???????????? ?????????????? ????????????.',
                'logoName' => 'cianao.jpg',
            ],
            [
                'name' => '?????????????????? Cianao ?? ???????????? 3',
                'category' => $newsCategory,
                'author' => $author,
                'ref' => self::REFERENCE_NEWS_CIANAO_3,
                'description' => '<p>???????????????????? ???????????? ?? AliExpress ?????????? ?????? ??????????. ?? ?????????? 2020 ???????? ?????????????????????????? ???????????????? Cainiao ?????????????????? ???????????? ?? ???????????? ????????????-???????????? &mdash; ?????????????????????? ???????? ???????????????????? ?????? ???????????????? ?????????????? ?? AliExpress. ???????????? ???????????????????????? ???????? ???????????????????? ?? ???????????????????? ?????? ???????????????? 11 ????????????.</p>

<p>???????????? ???????????????? ???????????? ?? ???????????????????????? ??????????????, ?????? ???????????????????? ???????????? ???????????? ????????????????. ???????????????????????? ???? ?????????????????????????????? ???????????????? ?????????????????????? ???????? &laquo;Cainiao&raquo; ?? ???????????? - ???????????? ???????????????? ????????????????, ?????? ???????????????? ?????????????????? ?????????????? ?????????? 1&nbsp;000 ?????????????? ???????????? ???? ???????? ????????????. ????????????, ???? ???????????? ????????????, ?????????????????? ???????????? ?????????????????????? ???????????? ?? ???????????? ?? ???????????????????? ??????????????.</p>

<p>?????????? ???????? ?????????? ???????????????? ?????????????? ???????????????? ?????????????? ?????????????????????????? ?????? ?????????????????? ?????????????? ?? AliExpress. ?????? ?????????? ???????????????????????????? ?????????????????? ?????????????????? ?????? ?????????????????? ?? ?????????? ?????????????????????? ???????????????? ?????? ??????????????????????.</p>

<h2><strong>?????????????????? Cainiao ?? ????????????</strong></h2>


<p>????????????????, ???????? ???? ?????????????? ???????????????? ?????? ?????? ?????????????????????? ?????????????????? ???????????? ????????????:</p>

<ol>
	<li>???????????????? ??????????, 17 ??4. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 23:00</li>
	<li>???????? ????????????????, 95 ????2. ???????????? ????????????: ??????????????????????????</li>
	<li>??????????????????????, 40/17. ???????????? ????????????: ?????????????????? ?? 09:00 ???? 22:00</li>
	<li>???????????????? 3-??, 53. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 23:00</li>
	<li>???? ??????????????????????????. ???????????? ????????????: ?????????????????? ?? 10:00 ???? 21:00</li>
	<li>???????????????????????? 2-??, 32 ??1</li>
	<li>?????????????????? ??????????, 17 ??2. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 22:00</li>
	<li>?????????????? ??????????????????, 30 ????1. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 21:00</li>
	<li>????????????????????, 15. ???????????? ????????????: ?????????????????? ?? 08:00 ???? 23:00</li>
	<li>????????????????????, 36. ???????????? ????????????: ?????????????????? ?? 09:00 ???? 21:00</li>
</ol>',
                'sort' => 0,
                'metaTitle' => '?????????????????? Cianao ??? ???????????? ???????????????? ???? ??????????',
                'metaDescription' => '???????????? ???????????? ?????????????? ???????????????????? Cianao ?? ????????????. ?????????? ???? ???????????? ?????????? ???????????? ???????? ???????????????????? ???????????? ???? ????????????, ?????? ?? ???????????? ?????????????? ????????????.',
                'logoName' => 'cianao.jpg',
            ],

            [
                'name' => '???????????????????? ?????????????? ?? 2021 ???????? ???? ??????????????????????',
                'category' => $helpCategory,
                'author' => $author,
                'ref' => self::REFERENCE_HELP_PAYMENT,
                'description' => '<p>?? 2020 ???????? ???????????????? ?????????? ?????????????????????????? ?????????? ???? ???????????? AliExpress ?? ????????????. ?????????? ?????????? ?????????????? ?????????????? ???????????????????? ???????????????? ???????????????????? 500 ????????. ???? ???????????? 2020 ???????? ?????????? ?????????????????????? ???? 200 ???. ????????????, ???? ?????? ?????? ?????????? ?????? ?????????? ????????????????, ?????? ?????????????? ?????????????????????????????? ?????????? ????????????????.</p>',
                'sort' => 0,
                'metaTitle' => '???????????????????? ?????????????? ?? 2021 ???????? ???? ??????????????????????',
                'metaDescription' => '???????????????????? ?????????????? ?? 2021 ???????? ???? ??????????????????????.',
                'logoName' => 'custody.jpg',
            ],
        ];
    }
}
