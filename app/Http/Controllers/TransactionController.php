<?php

namespace App\Http\Controllers;

use App\Account;
use App\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class TransactionController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/transaction/list",
     *      operationId="Get Transaction List",
     *      tags={"Transaction List"},
     * security={
     *  {"passport": {}},
     *   },
     *      summary="Get list of Transaction",
     *      description="Returns list of Transaction",
     *     @OA\Parameter(
     *      name="Search",
     *      description="filter finance name",
     *      in="query",
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
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
    public function transactionList(Request $request){

        $filter = $request->filter;

        if ($filter != null){
            $transaction = Transaction::where('name','like','%'.$filter.'%')
                ->paginate(10);
        }else{
            $transaction = Transaction::paginate(10);
        }

        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => [
                'transaction' => $transaction
            ],

            'message' => 'All Transaction pulled out successfully'

        ]);
    }

    //Create Transaction

    /**
     * @OA\Post(
     ** path="/api/transaction/create",
     *   tags={"Create Transaction"},
     *   summary="Create New Transaction",
     *   operationId="Create Transaction",
     * security={
     *  {"passport": {}},
     *   },
     *  @OA\Parameter(
     *      name="Finance Name",
     *      description="input your finance name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="Finance Account",
     *     description="select finance account by id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="amount",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function transactionStore(Request $request){

        $findAccount = Account::find($request->account_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'account_id' => 'required',
            'amount' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }


        $transaction = new Transaction();

        $transaction->transaction_date = Carbon::now();
        $transaction->name = $request->name;
        $transaction->account_id = $request->account_id;
        $transaction->account_name = $findAccount->name;
        $transaction->amount = $request->amount;
        $transaction->description = $request->description;

        $transaction->save();

        $success['name'] =  $transaction->name;
        return response()->json([
            'success' => $success,
            'status_code' => Response::HTTP_OK,
        ])->setStatusCode(Response::HTTP_CREATED);
    }

    // Transaction Details

    /**
     * @OA\Post(
     ** path="/api/transaction/detail/{id}",
     *   tags={"Transaction Detail"},
     *   summary="Transaction Detail",
     *   operationId="Transaction Detail",
     * security={
     *  {"passport": {}},
     *   },
     *   @OA\Parameter(
     *      name="id",
     *     description="input id transaction want to be show",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function transactionDetails($id){
        $transDetail = Transaction::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => [
                'details' => [
                    'name' => $transDetail->name,
                    'account_name' => $transDetail->account_name,
                    'amount' => $transDetail->amount,
                    'description' => $transDetail->description
                ]
            ],

            'message' => 'Transaction detail pulled out successfully'
        ]);
    }

    //Update Transaction

    /**
     * @OA\Post(
     ** path="/api/transaction/update/{id}",
     *   tags={"Update Transaction"},
     *   summary="Update Transaction",
     *   operationId="Update Transaction",
     *     security={
     *  {"passport": {}},
     *   },
     *     @OA\Parameter(
     *      name="id",
     *     description="input id transaction want to be update",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="Finance Name",
     *     description="input your finance name",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *     @OA\Parameter(
     *      name="Finance Account",
     *     description="select finance account by id",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="amount",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Parameter(
     *      name="description",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function transactionUpdate(Request $request,$id){
        $transaction = Transaction::findOrFail($id);

        $findAccount = Account::findOrFail($request->account_id);

        $transaction->transaction_date = Carbon::now();
        $transaction->name = $request->name;
        $transaction->account_id = $request->account_id;
        $transaction->account_name = $findAccount->name;
        $transaction->amount = $request->amount;
        $transaction->description = $request->description;

        $transaction->save();

        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => [
                'details' => [
                    'name' => $transaction->name,
                    'account_name' => $transaction->account_name,
                    'amount' => $transaction->amount,
                    'description' => $transaction->description
                ]
            ],

            'message' => 'Update successfully'
        ]);
    }

    //Delete Account
    /**
     * @OA\Post(
     ** path="/api/transaction/delete/{id}",
     *   tags={"Delete Transaction"},
     *   summary="Delete Transaction",
     *   operationId="Transaction Details",
     * security={
     *  {"passport": {}},
     *   },
     *   @OA\Parameter(
     *      name="id",
     *     description="input id transaction want to be delete",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="integer"
     *      )
     *   ),
     *
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function transactionDelete($id){
        $transaction = Transaction::findOrFail($id);

        $transaction->delete();

        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => [
                'transaction' => $transaction->name,

            ],

            'message' => 'Delete Transaction successfully'
        ]);
    }
}
