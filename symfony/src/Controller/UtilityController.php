<?php

namespace App\Controller;

use Monolog\Level;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/utility', name: 'app_utility_')]
class UtilityController extends AbstractController
{
    #[Route('/redmine/{data}', name: 'redmine', requirements: ['data' => '.+'], defaults: ['data' => ''])]
    public function redmine(string $data, Request $request, HttpClientInterface $client, LoggerInterface $logger): Response {
        /*
         * This will act as a proxy pass through to the redmine api to remove CORS restrictions that the current Redmine
         * server is not able to respond to
         */

        $url = $this->getParameter('app.redmine_url');
        $url .= $data;

        $url .= '?' . http_build_query($request->query->all());

        $logger->debug('url constructed: ' . $url);
        try {
            $response = $client->request($request->getMethod(), $url, [
                'headers' => [
                    'X-Redmine-API-Key' => $this->getParameter('app.redmine_api_token')
                ]
            ]);
            return new Response($response->getContent(), $response->getStatusCode(), $response->getHeaders());
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            return new Response($e->getTraceAsString());
        }
    }

    #[Route('/favicon.php', name: 'favicon')]
    public function favicon(Request $request, LoggerInterface $logger) {
        $max = 9;
        $max_symbol = '!';

        /* we can only deal with at most 4 values */
        $numbers = array_slice($request->query->all('number'), 0, 4);
        $max_symbol = $request->query->get('max_symbol', $max_symbol);
        /* only allow an override on the max if we are doing a single number */
        if(1 === count($numbers)) $max = $request->query->get('max', $max);
        array_walk($numbers, function(&$number) use ($max, $max_symbol){ $number = ($number <= $max) ? $number : $max_symbol; });

        $background = $request->query->get('bg', '194514');
        $foreground = $request->query->get('fg', 'e6e6e6');
        list($bg_r, $bg_g, $bg_b) = sscanf('#' . $background, "#%02x%02x%02x");
        list($fg_r, $fg_g, $fg_b) = sscanf('#' . $foreground, "#%02x%02x%02x");

        $settings = [
            1 => [
                ['size' => 16, 'x' => 10, 'y' => 24]
            ],
            2 => [
                ['size' => 12, 'x' => 10, 'y' => 14],
                ['size' => 12, 'x' => 10, 'y' => 30]
            ],
            3 => [
                ['size' => 12, 'x' => 10, 'y' => 14],
                ['size' => 12, 'x' => 0, 'y' => 30],
                ['size' => 12, 'x' => 20, 'y' => 30]
            ],
            4 => [
                ['size' => 12, 'x' => 0, 'y' => 14],
                ['size' => 12, 'x' => 20, 'y' => 14],
                ['size' => 12, 'x' => 0, 'y' => 30],
                ['size' => 12, 'x' => 20, 'y' => 30]
            ]
        ];

        $logger->log(Level::Debug, 'Settings', [
            'max' => $max,
            'max_symbol' => $max_symbol,
            'coords' => $settings,
            'background' => [
                'raw' => $background,
                'r' => $bg_r,
                'g' => $bg_g,
                'b' => $bg_b
            ],
            'foreground' => [
                'raw' => $foreground,
                'r' => $fg_r,
                'g' => $fg_g,
                'b' => $fg_b
            ],
            'input' => $numbers
        ]);

        // base image
        $generated_image = imagecreate(32, 32);
        imagecolorallocate($generated_image, $bg_r, $bg_g, $bg_b);

        if (count($numbers)) {
            $setting = $settings[count($numbers)];
            $text_color = imagecolorallocate($generated_image, $fg_r, $fg_g, $fg_b);
            foreach ($numbers as $index => $number) {
                if(1 === count($numbers) && $number > 9) {
                    $logger->debug('Overriding x coordinates');
                    $setting[$index]['x'] = 2;
                }
                imagefttext(
                    $generated_image,
                    $setting[$index]['size'],
                    0,
                    $setting[$index]['x'],
                    $setting[$index]['y'],
                    $text_color,
                    $this->getParameter('kernel.project_dir') . '/resources/font/ARIBL0.ttf',
                    $number
                );
            }
        }
        // Return the actual image as a proper PNG response
        header('Content-Type: image/png');
        header('Cache-Control: max-age=604800, public');
        imagepng($generated_image); // Write binary data to output stream

        imagedestroy($generated_image); // Ensure memory is freed

        return new Response();
    }
}
