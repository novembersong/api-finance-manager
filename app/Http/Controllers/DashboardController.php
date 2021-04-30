<?php

namespace App\Http\Controllers;

use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/transaction/summary",
     *      operationId="Get Transaction Summary List",
     *      tags={"Transaction Summary"},
     *     security={
     *  {"passport": {}},
     *   },
     *      summary="Get list of Transaction",
     *      description="Returns list of Transaction Summary",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *  )
     */
    public function transactionSummary(){
        $labels = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec',
        ];

        foreach($labels as $key=>$month){
            $transactionMonthly = Transaction::whereMonth('created_at',$key)
                ->sum('amount');

            $labelMonth[]=$month;
            $amountMonthly[]=$transactionMonthly;
        }

//        $day = strtotime('+1 month');

        for ($i = 0; $i < 30; $i++)
        {
            $timestamp = time();
            $tm = 86400 * $i;
            $tm = $timestamp - $tm;
            $the_date = date("Y-m-d", $tm);
            $dates[] = $the_date;
        }


        foreach ($dates as $date){
            $transactionDaily = Transaction::whereDate('created_at','=',$date)
                ->sum('amount');

            $labelDay[]=$date;
            $amountDaily[]=$transactionDaily;
        }


        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => [
                'monthlySummary' => [
                    'labelMonth' => $labelMonth,
                    'amountMonthly' => $amountMonthly,
                ],
                'dailySummary' =>[
                    'labelDay' => $labelDay,
                    'amountDaily' => $amountDaily
                ]

            ],

            'message' => 'All Transaction pulled out successfully'

        ]);

    }
}
