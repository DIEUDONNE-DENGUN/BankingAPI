<?php


namespace App\Customer\Profile\Repositories;

use App\Customer\Accounts\Constants\AccountStatus;
use \App\Customer\Commons\BaseRepository;
use App\Customer\Profile\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @param User
 * @package App\Customer\Profile\Repositories
 * @author Dieudonne Takougang
 * Handle user data layer persistence to database
 */
class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * custom method to declare the associated models to account model to be returned during findById
     * @param integer $userId
     * @return Model
     */
    public function findUserById($userId)
    {
        $relationships = ['accounts'];
        return parent::findById($userId, $relationships);
    }

    /**
     * @param string $email
     * @return Model
     */
    public function findUserByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * check if a user already has an existing email address
     * @param $email
     * @return boolean
     */
    public function hasEmailAddress($email)
    {
        $emailExist = $this->findUserByEmail($email);
        return empty($emailExist) ? false : true;
    }

    /**
     * check if a user account is active for bank operations
     * @param string $email
     * @return boolean
     */
    public function isAccountActive($email)
    {
        $isAccountActive = $this->model->where('status', AccountStatus::$ACCOUNT_ACTIVE)
            ->where('email', $email)
            ->first();
        return empty($isAccountActive) ? false : true;
    }
}
