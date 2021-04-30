<?php

namespace App\Http\Controllers;

use App\Account;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Validator;

class FinanceController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/account/list",
     *      operationId="getAccountList",
     *      tags={"Account List"},
     * security={
     *  {"passport": {}},
     *   },
     *      summary="Get list of Account",
     *      description="Returns list of Account",
     * @OA\Parameter(
     *      name="Search",
     *      description="filter account name",
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
    public function accountList(Request $request){

        $filter = $request->filter;

        if ($filter != null){
            $account = Account::where('name','like','%'.$filter.'%')
                ->paginate(10);
        }else{
            $account = Account::paginate(10);
        }

        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => [
                'users' => $account
            ],

            'message' => 'All Finance pulled out successfully'

        ]);

    }

    //Create Account

    /**
     * @OA\Post(
     ** path="/api/account/create",
     *   tags={"Create Account"},
     *   summary="Create New Account",
     *   operationId="Create Account",
     * security={
     *  {"passport": {}},
     *   },
     *  @OA\Parameter(
     *      name="name",
     *     description="eg. cash,bank,etc",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="type",
     *     description="eg. cash/bank/ewallet",
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
    public function accountStore(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $input = $request->all();
        $account = Account::create($input);
        $success['name'] =  $account->name;
        return response()->json([
            'success' => $success,
            'status_code' => Response::HTTP_OK,
        ])->setStatusCode(Response::HTTP_CREATED);

    }

    // Account Detail

    /**
     * @OA\Post(
     ** path="/api/account/detail/{id}",
     *   tags={"Account Detail"},
     *   summary="Account Detail",
     *   operationId="AccountDetails",
     * security={
     *  {"passport": {}},
     *   },
     *   @OA\Parameter(
     *      name="id",
     *     description="input id account",
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
    public function accountDetails($id){
        $accDetail = Account::findOrFail($id);
        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => [
                'details' => [
                   'name' => $accDetail->name,
                    'type' => $accDetail->type,
                    'description' => $accDetail->description
                ]
            ],

            'message' => 'Finance detail pulled out successfully'
        ]);

    }

    //Account Edit

    /**
     * @OA\Post(
     ** path="/api/account/update/{id}",
     *   tags={"Edit Account"},
     *   summary="Update Account",
     *   operationId="Update Account",
     * security={
     *  {"passport": {}},
     *   },
     *  @OA\Parameter(
     *      name="id",
     *     description="input id account want to be update",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="Account Name",
     *     description="eg. cash,bank,etc",
     *      in="query",
     *      required=true,
     *      @OA\Schema(
     *           type="string"
     *      )
     *   ),
     *  @OA\Parameter(
     *      name="type",
     *     description="eg. cash/bank/ewallet",
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
    public function accountUpdate(Request $request, $id){
        $account = Account::findOrFail($id);

        $account->name = $request->name;
        $account->type = $request->type;
        $account->description = $request->description;

        $account->save();

        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => [
                'details' => [
                    'name' => $account->name,
                    'type' => $account->type,
                    'description' => $account->description
                ]
            ],

            'message' => 'Update successfully'
        ]);

    }

    //Delete Account

    /**
     * @OA\Post(
     ** path="/api/account/delete/{id}",
     *   tags={"Delete Account"},
     *   summary="Delete Account",
     *   operationId="Account Details",
     * security={
     *  {"passport": {}},
     *   },
     *   @OA\Parameter(
     *      name="id",
     *     description="input id account want to be deleted",
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
    public function accountDelete($id){

        $account = Account::findOrFail($id);

        $account->delete();

        return response()->json([
            'status' => 'success',
            'status_code' => Response::HTTP_OK,
            'data' => [
                'users' => $account->name,

            ],

            'message' => 'Delete Finance successfully'
        ]);
    }
}
