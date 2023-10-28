<?php

namespace App\Controller;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Exception\CommonMarkException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class PostsController extends AbstractController
{

    #[Route('/posts', name: 'app_posts')]
    public function index(Request $request, KernelInterface $kernel, ParameterBagInterface $params): Response
    {
        // Get ?limit query parameter if it exists.
        $limit = $request->query->get('limit') ?? $params->get('posts.post_limit');
        $year = $request->query->get('year') ?? null;
        $month = $request->query->get('month') ?? null;

        // Search for posts in /posts directory that are older than today's date.
        // The format of the post files is YYYY-MM-DD_slug.md.
        $projectDir = $kernel->getProjectDir();
        $directory = $projectDir . $params->get('posts.post_directory');
        $posts = $this->getPosts($directory, $limit, $year, $month);

        return $this->render('posts/index.html.twig', [
          'posts' => $posts,
        ]);
    }

    #[Route('posts/list', name: 'app_posts_list')]
    public function list(Request $request, KernelInterface $kernel, ParameterBagInterface $params)
    {
        // Get ?limit query parameter if it exists.
        $limit = $request->query->get('limit') ?? $params->get('posts.post_limit');
        $year = $request->query->get('year') ?? null;
        $month = $request->query->get('month') ?? null;

        // Search for posts in /posts directory that are older than today's date.
        // The format of the post files is YYYY-MM-DD_slug.md.
        $projectDir = $kernel->getProjectDir();
        $directory = $projectDir . $params->get('posts.post_directory');
        $posts = $this->getPosts($directory, $limit, $year, $month);

        return $this->render('posts/list.html.twig', [
          'posts' => $posts,
        ]);
    }

    #[Route('/posts/show/{slug}', name: 'app_posts_show')]
    public function show(string $slug, CommonMarkConverter $commonMarkConverter, KernelInterface $kernel, Request $request, ParameterBagInterface $params): Response
    {
        $projectDir = $kernel->getProjectDir();
        $directory = $projectDir . $params->get('posts.post_directory');

        $fileContent = $this->getPost($directory, $slug);

        [$markdownContent, $metadata] = $this->separateFrontmatterFromMarkdownContent($fileContent);

        try {
            $htmlContent = $commonMarkConverter->convert($markdownContent);
        } catch (CommonMarkException $e) {
            throw $this->createNotFoundException("The content could not be parsed: \n\n" . $e->getMessage());
        }

        return $this->render('posts/show.html.twig', [
          'content' => $htmlContent,
          'metadata' => $metadata,
        ]);
    }

    #[Route('/posts/show/{slug}/teaser', name: 'app_posts_teaser')]
    public function teaser(string $slug, CommonMarkConverter $commonMarkConverter, KernelInterface $kernel, Request $request, ParameterBagInterface $params): Response
    {
        $projectDir = $kernel->getProjectDir();
        $directory = $projectDir . $params->get('posts.post_directory');

        $fileContent = $this->getPost($directory, $slug);

        [$markdownContent, $metadata] = $this->separateFrontmatterFromMarkdownContent($fileContent);

        return $this->render('posts/teaser.html.twig', [
          'slug' => $slug,
          'metadata' => $metadata,
        ]);
    }

    /**
     * @param string $directory
     * @param string $slug
     *
     * @return string|false
     */
    public function getPost(string $directory, string $slug): bool|string
    {

        $files = glob($directory . '/*.md');
        $matchingFile = null;

        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);

            // Break filename into date and slug parts
            preg_match('/(\d{4}-\d{2}-\d{2})_(.*)/', $filename, $matches);

            // Skip files that don't match the pattern
            if (!$matches) {
                continue;
            }

            [$full, $fileDate, $fileSlug] = $matches;
            if ($slug === $fileSlug) {
                $matchingFile = $file;
                break;
            }
        }

        if (!$matchingFile) {
            throw $this->createNotFoundException('The post does not exist');
        }

        return file_get_contents($matchingFile);
    }

    /**
     * @param bool|string $fileContent
     *
     * @return array
     */
    public function separateFrontmatterFromMarkdownContent(bool|string $fileContent): array
    {
        $parts = explode('---', $fileContent, 3);
        if (count($parts) >= 3) {
            [$frontmatter, $markdownContent] = [trim($parts[1]), trim($parts[2])];
            $metadata = Yaml::parse($frontmatter);
        } else {
            $markdownContent = $fileContent;
            $metadata = [];
        }
        return [$markdownContent, $metadata];
    }

    /**
     * @param string $directory
     * @param int|string $limit
     * @param $matches
     * @param mixed $year
     * @param mixed $month
     *
     * @return array
     */
    public function getPosts(string $directory, int|string $limit, mixed $year, mixed $month): array
    {
        $files = glob($directory . '/*.md');
        // Reverse files to be in descending order.
        $reversed_files = array_reverse($files);
        $posts = [];
        foreach ($reversed_files as $file) {
            // Stop after $limit posts.
            if (count($posts) >= $limit) {
                break;
            }

            $filename = pathinfo($file, PATHINFO_FILENAME);
            preg_match('/(\d{4}-\d{2}-\d{2})_(.*)/', $filename, $matches);
            if (!$matches) {
                continue;
            }
            [$full, $fileDate, $fileSlug] = $matches;

            // If year and month are set, only show posts from that year and month.
            if ($year && $month) {
                if ($year !== substr($fileDate, 0, 4) || $month !== substr($fileDate, 5, 2)) {
                    continue;
                }
            }

            // If year is set, only show posts from that year.
            if ($year && !$month) {
                if ($year !== substr($fileDate, 0, 4)) {
                    continue;
                }
            }

            if (strtotime($fileDate) < strtotime('today')) {
                $posts[] = [
                  'date' => $fileDate,
                  'slug' => $fileSlug,
                ];
            }
        }
        return $posts;
    }
}
