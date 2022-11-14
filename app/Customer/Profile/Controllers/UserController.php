<?php


namespace App\Customer\Profile\Controllers;

use App\Customer\Accounts\Responses\AccountResponse;
use App\Customer\Profile\Requests\CreateUserProfileRequest;
use App\Customer\Profile\Requests\LoginUserRequest;
use App\Customer\Profile\Responses\UserResponse;
use App\Customer\Profile\Services\UserService;
use \App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @param UserService
 * @package App\Customer\Profile\Controllers
 * @author Dieudonne Takougang
 * Class UserController
 */
class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Create a customer user account profile
     * @param CreateUserProfileRequest $request
     */
    public function createUserProfile(CreateUserProfileRequest $request)
    {
        //save user profile details
        $userProfile = $this->userService->createUserProfile($request->getUserProfileDTO());
        //map the created user profile to user resource for response
        $userResponse = new UserResponse($userProfile);
        $userResponse['message'] = 'Customer profile created successfully';
        // send back mapped response
        return response()->json($userResponse, 201);
    }

    /**
     * Login into customer profile to get access token
     * @param LoginUserRequest
     */
    public function login(LoginUserRequest $request)
    {
        $accessToken = $this->userService->login($request->getUserLoginDTO());

        return response()->json($accessToken);
    }

    /**
     * get all user or customer bank accounts created if exist
     * @param $customerId
     */
    public function getUserBankAccounts(Request $request, $customerId)
    {
        $accounts = $this->userService->getCustomerBankAccounts($customerId);
        //check if the customer has at least a bank account attached,
        if ($accounts->isEmpty()) return response()->json(['message' => "No associated customer bank accounts found for this user", 'customerId' => $customerId]);
        //user has bank accounts associated
        $response = ['data' => AccountResponse::collection($accounts)];
        return response()->json($response);
    }
}
