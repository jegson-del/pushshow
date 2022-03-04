<?php

namespace App\Helpers;
use App\Models\Ledger;

class TransactionLogger
{   

    protected $code;

    protected $model;

    protected $amount;

    protected $ref = 'PUT-';

    protected $reference;

    protected $oldBalance;

    protected $newBalance;

    protected $entity;

    public function __construct($model,$code,$amount,$oldBalance,$newBalance,$entity)
    {
        $this->model = $model;
        $this->code = $code;
        $this->amount = $amount;
        $this->oldBalance = $oldBalance;
        $this->newBalance = $newBalance;
        $this->entity = $entity;
    }

    public function financialHandler()
    {
        $diff = $this->newBalance - $this->oldBalance;
        $type = $diff > 0 ? 'Credit' : 'Debit';
        $description = config('maps.flow_codes')[$this->code];
        Ledger::create([
            'user_id' => $this->model->id,
            'user_type' => get_class($this->model),
            'amount' => $this->amount,
            'oldBalance' => $this->oldBalance,
            'newBalance' => $this->newBalance,
            'description' => $description,
            'entity' => get_class($this->entity),
            'entity_id' => $this->entity->id,
            'type' => $type,
            'code' => $this->code,
        ]);

        $this->model->wallet_balance = $this->newBalance;
        $this->model->save();
    }

}