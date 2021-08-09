<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use App\Interfaces\Services\ITransactionService;
use App\Exceptions\TransactionDeniedException;
use App\Exceptions\NotFoundEntityException;
use App\Exceptions\DuplicatedPaymentException;
use App\Exceptions\CantMakePaymentException;
use App\Entities\TransactionEntity;

class TransactionController extends Controller
{

    // Rules to validate the request
    private $validationRules;

    // Service to users
    private $transactionService;

    public function __construct(ITransactionService $transactionService)
    {
        $this->transactionService = $transactionService;

        $this->validationRules = [
            'payee' => 'required|exists:users,id',
            'payer' => 'required|exists:users,id',
            'value' => 'required|numeric|min:0'
        ];
    }

    /**
     * Make a transaction between users
     * @param Request
     * @return Response
     */
    public function make(Request $request)
    {
        $data = $this->validate($request, $this->validationRules);

        $transaction = new TransactionEntity(
            null,
            $data['payee'],
            $data['payer'],
            $data['value']
        );

        try {
            $this->transactionService->makeTransaction($transaction);

            return response('', 201);
        } catch (CantMakePaymentException $e) {
            return response($e->getMessage(), 400);
        } catch (DuplicatedPaymentException $e) {
            return response($e->getMessage(), 400);
        } catch (TransactionDeniedException $e) {
            return response($e->getMessage(), 400);
        } catch (NotFoundEntityException $e) {
            return response($e->getMessage(), 404);
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    /**
     * Add founds in user account
     * @return Response
     */
    public function addFunds(Request $request)
    {
        $data = $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0'
        ]);

        try {
            $this->transactionService->addFunds(
                new TransactionEntity(
                    null,
                    $data['user_id'],
                    null,
                    $data['amount']
                )
            );

            return response('', 201);
        } catch (NotFoundEntityException $e) {
            return response($e->getMessage(), 404);
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
