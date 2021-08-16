<?php

namespace App\Http\Controllers;

use Goutte;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    public function getData(){
        $crawler = Goutte::request('GET', 'http://103.81.104.98/DATA/NAS1/E-Books/%e0%a7%ad%e0%a7%a7%e0%a7%a8%20%e0%a6%9f%e0%a6%be%20%e0%a6%ac%e0%a6%be%e0%a6%82%e0%a6%b2%e0%a6%be%20%e0%a6%89%e0%a6%aa%e0%a6%a8%e0%a7%8d%e0%a6%af%e0%a6%be%e0%a6%b8/');
        $crawler->filter('table > tr > td > a')->each(function ($node) {
            $href = $node->extract(array('href'));
            dump($href[0]);
        });
    }

    public function getPDFInfo($end_point): array
    {
        $crawler = Goutte::request('GET', 'http://103.81.104.98/DATA/NAS1/E-Books/%e0%a7%ad%e0%a7%a7%e0%a7%a8%20%e0%a6%9f%e0%a6%be%20%e0%a6%ac%e0%a6%be%e0%a6%82%e0%a6%b2%e0%a6%be%20%e0%a6%89%e0%a6%aa%e0%a6%a8%e0%a7%8d%e0%a6%af%e0%a6%be%e0%a6%b8/'.$end_point);
        return $this->filtering($crawler);
    }

    public function getPDFInfoOnly(): array
    {
        $crawler = Goutte::request('GET', 'http://103.81.104.98/DATA/NAS1/E-Books/%e0%a7%ad%e0%a7%a7%e0%a7%a8%20%e0%a6%9f%e0%a6%be%20%e0%a6%ac%e0%a6%be%e0%a6%82%e0%a6%b2%e0%a6%be%20%e0%a6%89%e0%a6%aa%e0%a6%a8%e0%a7%8d%e0%a6%af%e0%a6%be%e0%a6%b8/');
        return $this->filtering($crawler);
    }

    public function filtering($crawler): array
    {
        $data = $crawler->filter('table > tr > td > a')->each(function ($node) {
            $href = $node->extract(array('href'));

            $pdf_link = $href[0];
            $filter1 = $pdf_link;
            if (preg_match('/-/', $pdf_link)) {
                $filter1 = preg_replace('/-/', ' ', $pdf_link);
            }

            if (preg_match('/_/', $filter1)) {
                $filter1 = preg_replace('/_/', ' ', $filter1);
            }

            if (preg_match('/.pdf/', $filter1)) {
                $filter1 = preg_replace('/.pdf/', '', $filter1);
            }

            if (preg_match('/%/', $filter1)) {
                $filter1 = preg_replace('/%/', ' ', $filter1);
            }

            $filter1 = trim(preg_replace('/\s\s+/', ' ', str_replace("\n", " ", $filter1)));

            if (preg_match('/.pdf/', $pdf_link)) {
                return ['pdf_name' => $filter1, 'pdf_url' => $href[0]];
            } else {
                return ['pdf_name' => $pdf_link, 'pdf_url' => $href[0]];
            }
        });
        return ["data" => $data];
    }
}
