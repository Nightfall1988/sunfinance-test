<?php

namespace App\Controller;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\PaymentManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PaymentRepository;


class PaymentController extends AbstractController
{
    public PaymentManager $paymentManager;

    public PaymentRepository $paymentRepository;

    public function __construct(PaymentManager $paymentManager, PaymentRepository $paymentRepository)
    {
        $this->paymentManager = $paymentManager;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @Route("/payment", name="app_payment")
     * @return Response
     */
    public function index(): Response
    {
        $filePath = __DIR__ . '/../../exampleData/import/payments.csv';

        if (file_exists($filePath)) {
            $this->paymentManager->importCSV($filePath);
            $payments = $this->paymentRepository->findAll();
            
            return $this->render('default/index.html.twig', [
                'payments' => $payments,
            ]);


            return new Response('File found');
        } else {
            return new Response('File not found');
        }
    }
}
