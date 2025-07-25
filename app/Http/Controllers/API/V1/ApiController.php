<?php

namespace App\Http\Controllers\API\V1;

use App\Models\OurProduct;
use App\Models\Testimonial;
use Carbon\Carbon;
use App\Models\Cms;
use App\Models\Blog;
use App\Models\Plan;
use App\Models\Team;
use App\Models\User;
use App\Models\Addon;
use App\Models\Banner;
use App\Models\Client;
use App\Models\Contact;
use App\Models\MemberBatch;
use App\Models\MemberModule;
use App\Models\Membership;
use App\Models\MemberStartupModule;
use App\Models\Modules;
use App\Models\NotificationSetting;
use App\Models\OurCourses;
use App\Models\PlanPurchase;
use App\Models\Service;
use App\Models\Services;
use App\Models\UserDevices;
use App\Models\Video;
use App\Models\Setting;
use App\Models\StartupService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AddOnPurchase;
use App\Services\CurlApiService;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRequest;
use App\Models\MemberModuleSubject;
use App\Models\OneTimeMeeting;
use App\Models\RevisionBatch;
use App\Models\StartupServiceModules;
use Illuminate\Support\Facades\Hash;
use App\Services\FcmNotificationService;
use Illuminate\Support\Facades\Validator;


class ApiController extends Controller
{
    public $per_page_show;
    public $base_url;
    public $profile_path;
    public $banner_path;
    public $client_path;
    public $blog_path;
    public $plan_path;
    public $service_path;
    public $visit_path;
    public $icon_path;
    public $hr_path;
    public $revision_batch_path;
    protected $fcmNotificationService;
    protected $curlApiService;

    public function __construct(CurlApiService $curlApiService, FcmNotificationService $fcmNotificationService)
    {
        $this->per_page_show = 50;
        $this->base_url = url('/');
        // $this->base_url = env('APP_URL');
        $this->profile_path = 'public/profile_images/';
        $this->banner_path = '/banners/';
        $this->client_path = '/clients/';
        $this->revision_batch_path = '/revision_batch_receipts/';
        $this->blog_path = '/blogs/';
        $this->plan_path = '/plans/';
        $this->visit_path = '/visit/';
        $this->icon_path = '/icon/';
        $this->hr_path = '/hr/';
        $this->service_path = '/services/';
        $this->fcmNotificationService = $fcmNotificationService;
        $this->curlApiService = $curlApiService;
    }

    /**
     * registr/Otp send to mobile.
     */
    public function sendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|min:10|digits:10',
                'country_code' => 'required|digits:2',
            ]);

            if ($validator->fails()) {
                $result['status'] = false;
                $result['message'] = $validator->errors()->first();
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

            $mobile = $request->mobile;
            $base_url = $this->base_url;
            $otp = '0096'; //rand(1000, 9999);
            $otpExpiresAt = Carbon::now()->addMinutes(1);
            DB::enableQueryLog();

            // Send OTP via SMS
            $phoneNumber = $mobile;
            $optionalKey = $request->hashKey;
            $chkUserData = User::where('phone_number', $mobile)->where('status', 1)->first();

            $message = 'Your login OTP is ' . $otp . '. Headway Business Solutions' . $optionalKey . '';
            $data['SenderID'] = 'HEADAB';
            $data['SMSType'] = 4;
            $data['Mobile'] = $phoneNumber;
            $data['EntityID'] = env('API_ENTITY_ID');
            $data['TemplateID'] = env('API_Template_ID');
            $data['MsgText'] = $message;
            if ($chkUserData) {
                if ($mobile != '9879879879' && $mobile != '7874600096' && $mobile != '7567300096' && $mobile != '7874500096') { // remove once live apk
                    $chkUserData->otp = $otp;
                    $chkUserData->otp_expires_at = $otpExpiresAt;
                    $chkUserData->save();
                    $response = $this->curlApiService->postRequest(env('API_KEY'), $data);
                    if (strpos($response, "ok") !== false) {
                        $result['status'] = true;
                        $result['message'] = "OTP SEND";
                        $result['data'] = (object) [];
                        // return response()->json($result, 200);
                    } else {
                        $result['status'] = false;
                        $result['message'] = "OTP NOT SEND" . $response;
                        $result['data'] = (object) [];
                        // return response()->json($result, 200);
                    }
                } else {
                    $data = [];
                    $chkUserData->otp = '0096';
                    $chkUserData->otp_expires_at = $otpExpiresAt;
                    $chkUserData->save();
                }
            } else {

                $response = $this->curlApiService->postRequest(env('API_KEY'), $data);
                if (strpos($response, "ok") !== false) {
                    $result['status'] = true;
                    $result['message'] = "OTP SEND";
                    $result['data'] = (object) [];
                    // return response()->json($result, 200);
                } else {
                    $result['status'] = false;
                    $result['message'] = "OTP NOT SEND" . $response;
                    $result['data'] = (object) [];
                    // return response()->json($result, 200);
                }
                $chkUser = new User();
                $chkUser->otp = $otp;
                $chkUser->phone_number = $phoneNumber;
                $chkUser->otp_expires_at = $otpExpiresAt;
                $chkUser->password = Hash::make('123456');
                $chkUser->save();
            }
            $chkUserData = User::where('phone_number', $mobile)->where('status', 1)->get();
            $data = $chkUserData->map(function ($user) use ($base_url, $otp) {
                return collect($user)->except(['password', 'email_verified_at', 'otp', 'otp_expires_at', 'remember_token'])
                    ->put('user_id', $user['id'])
                    ->put('otp', $otp)
                    ->put('avatar', ($user['avatar']) ? $base_url . $this->profile_path . $user['avatar'] : '')
                    ->toArray();
            })->first();


            return response()->json(['status' => true, 'message' => 'OTP sent successfully!', 'data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * User login with OTP.
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'country_code' => 'required|digits:2',
                'mobile' => 'required|digits:10',
                'otp' => 'required|digits:4',
                'device_type' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => (object) []
                ], 200);
            }

            $mobile = $request->mobile;
            $otp = $request->otp;
            $device_token = $request->device_token ?? '';
            $device_type = $request->device_type ?? '';
            $api_version = $request->api_version ?? '';
            $app_version = $request->app_version ?? '';
            $os_version = $request->os_version ?? '';
            $device_model_name = $request->device_model_name ?? '';
            $app_language = $request->app_language ?? '';
            $base_url = config('app.url'); // fallback
            $user = User::with('MemberBatch')
                ->where('phone_number', $mobile)
                ->where('status', 1)
                ->where('is_deleted', 0)
                ->first();

            if (
                !$user ||
                $user->otp !== $otp ||
                Carbon::now()->greaterThan(Carbon::parse($user->otp_expires_at))
            ) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP or OTP expired',
                    'data' => (object) []
                ], 200);
            }

            $token = JWTAuth::fromUser($user);

            $user->otp = null;
            $user->otp_expires_at = null;
            $user->remember_token = $token;
            $user->save();

            DB::table('user_devices')->insert([
                'user_id' => $user->id,
                'device_token' => $device_token,
                'device_type' => $device_type,
                'api_version' => $api_version,
                'app_version' => $app_version,
                'os_version' => $os_version,
                'device_model_name' => $device_model_name,
                'app_language' => $app_language,
                'login_token' => $token,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Use the same structure as in getProfile
            $data = collect($user)
                ->except(['password', 'email_verified_at', 'otp', 'otp_expires_at', 'remember_token', 'member_batch'])
                ->put('user_id', $user->id)
                ->put('headway_id', $user->MemberBatch ? (string) $user->MemberBatch->headway_id : '--')
                ->put('batch_number', $user->MemberBatch ? $user->MemberBatch->batch : '--')
                ->put('avatar', $user->avatar ? $base_url . $this->profile_path . $user->avatar : '')
                ->put('token', $token)
                ->toArray();

            return response()->json([
                'status' => true,
                'message' => 'Login successfully!',
                'data' => $data,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }


    /**
     * registr/Otp send to mobile.
     */
    public function resendOtp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'mobile' => 'required|min:10|digits:10',
                'country_code' => 'required|digits:2',
            ]);

            if ($validator->fails()) {
                $result['status'] = false;
                $result['message'] = $validator->errors()->first();
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

            $mobile = $request->mobile;
            $base_url = $this->base_url;
            $otp = '0096'; //rand(1000, 9999);
            $otpExpiresAt = Carbon::now()->addMinutes(1);
            DB::enableQueryLog();

            // Send OTP via SMS
            $phoneNumber = $mobile;
            $optionalKey = $request->hashKey;
            $chkUserData = User::where('phone_number', $mobile)->where('status', 1)->first();

            if (!$chkUserData) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found or inactive.',
                    'data' => (object) []
                ], 200);
            }
            $message = 'Your login OTP is ' . $otp . '. Headway Business Solutions' . $optionalKey . '';
            $data['SenderID'] = 'HEADAB';
            $data['SMSType'] = 4;
            $data['Mobile'] = $phoneNumber;
            $data['EntityID'] = env('API_ENTITY_ID');
            $data['TemplateID'] = env('API_Template_ID');
            $data['MsgText'] = $message;

            $chkUserData->otp = $otp;
            $chkUserData->otp_expires_at = $otpExpiresAt;
            $chkUserData->save();
            $response = $this->curlApiService->postRequest(env('API_KEY'), $data);
            if (strpos($response, "ok") !== false) {
                $result['status'] = true;
                $result['message'] = "OTP SEND";
                $result['data'] = (object) [];
                // return response()->json($result, 200);
            } else {
                $result['status'] = false;
                $result['message'] = "OTP NOT SEND" . $response;
                $result['data'] = (object) [];
                // return response()->json($result, 200);
            }

            return response()->json(['status' => true, 'message' => 'OTP sent successfully!', 'data' => []]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get dashboard data data.
     */
    public function getDashboardData(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $usersData = User::where('id', $user_id)->first();
            // dd($usersData);
            $banner = Banner::where('status', 1)->where('is_deleted', 0)->get();
            $banner = $banner->map(function ($user) use ($base_url) {
                return collect($user)->except(['password', 'role_id', 'email_verified_at'])
                    ->put('banner_image', ($user['image']) ? $base_url . $this->banner_path . $user['image'] : '')
                    ->toArray();
            })->toArray();
            $bannerPopup = [];
            foreach ($banner as $key => $bann) {
                if ($bann['is_popup'] == 1) {
                    // $banner['id'] = $bann['id'];
                    // $banner['title'] = $bann['title'];
                    // $banner['heading'] = $bann['heading'];
                    // $banner['desc'] = $bann['desc'];
                    // $banner['is_popup'] = $bann['is_popup'];
                    $bannerPopup['popup_image'] = $base_url . $this->banner_path . $bann['image'];
                } else {
                    $banners['id'] = $bann['id'];
                    $banners['title'] = $bann['title'];
                    $banners['heading'] = $bann['heading'];
                    $banners['desc'] = $bann['desc'];
                    $banners['is_popup'] = $bann['is_popup'];
                    $banners['popup_image'] = $base_url . $this->banner_path . $bann['image'];
                    $bannerData[] = $banners;
                }
            }
            $clients = Client::where('status', 1)->where('is_deleted', 0)->get();
            $clients = $clients->map(function ($client) use ($base_url) {
                return collect($client)->except(['password', 'role_id', 'email_verified_at', 'created_at', 'updated_at', 'status'])
                    ->put('client_image', ($client['image']) ? $base_url . $this->client_path . $client['image'] : '')
                    ->toArray();
            })->toArray();

            $blogs = Blog::where('status', 1)->where('is_deleted', 0)->first();
            if ($blogs) {
                $blogs = collect($blogs)
                    ->except(['password', 'role_id', 'email_verified_at', 'created_at', 'updated_at', 'status', 'category_id'])
                    ->put('blog_image', $blogs['image'] ? $base_url . $this->blog_path . $blogs['image'] : '')
                    ->toArray();
                $blogs = (object) $blogs;
            }

            $middlebanner = Banner::where('status', 1)->where('is_deleted', 0)->first();
            if ($middlebanner) {
                $middlebanner = collect($middlebanner)
                    ->except(['password', 'role_id', 'email_verified_at', 'created_at', 'updated_at', 'status', 'category_id'])
                    ->put('popup_image', $middlebanner['image'] ? $base_url . $this->banner_path . $middlebanner['image'] : '')
                    ->toArray();
                $middlebanner = (object) $middlebanner;
            }
            $activePlan = PlanPurchase::with('Plans')->where('user_id', $user_id)->where('purchase_status', 'Approved')->first();
            // dd($activePlan);
            if ($activePlan) {
                $userDataInfo['plan_name'] = ($activePlan) ? $activePlan->Plans->plan_name : '';
                $userDataInfo['plan_id'] = ($activePlan) ? $activePlan->Plans->id : '';
                $userDataInfo['total_price'] = ($activePlan->total_price) ? $activePlan->total_price : 00.00;
                $userDataInfo['plan_type'] = ($activePlan) ? $activePlan->Plans->plan_type : '';
                $userDataInfo['page_type'] = ($activePlan) ? $activePlan->Plans->page_type : '';
                $userDataInfo['plan_icon'] = ($activePlan) ? $base_url . $this->plan_path . $activePlan->Plans->image : '';
            } else {
                $userDataInfo = (object) [];
            }


            $activeplans = $userDataInfo;
            $we_do_title = 'What we do?';
            $we_do_info = 'Headway Business Solutions LLP goes beyond simply "coaching" and "consulting." We are committed to fostering long-term relationships with our clients, acting as trusted advisors and partners on their journey towards achieving their unique jewellery business aspirations.';
            $Isverify = $usersData->is_verify;

            $our_services = Services::select('id', 'name', 'sort_desc', 'image')->where('status', 1)->where('is_deleted', 0)->orderBy('id', 'DESC')->limit(2)->get();
            $our_services = $our_services->map(function ($our_services) use ($base_url, $token) {
                return collect($our_services)->except(['service_desc', 'parent_id', 'created_at', 'updated_at'])
                    ->put('id', $our_services['id'])
                    ->put('name', $our_services['name'])
                    ->put('sort_desc', $our_services['sort_desc'])
                    ->put('image', ($our_services['image']) ? $base_url . $this->service_path . $our_services['image'] : '')
                    ->toArray();
            })->toArray();
            $ourServices = $our_services;

            $jewellsSql = Client::select('id', 'name', 'city', 'image')->where('status', 1)->where('is_deleted', 0)->orderBy('id', 'DESC')->get();
            $jewellsSql = $jewellsSql->map(function ($jewellsSql) use ($base_url, $token) {
                return collect($jewellsSql)->except(['service_desc', 'parent_id', 'created_at', 'updated_at'])
                    ->put('id', $jewellsSql['id'])
                    ->put('name', $jewellsSql['name'])
                    ->put('city', $jewellsSql['city'])
                    ->put('image', ($jewellsSql['image']) ? $base_url . $this->client_path . $jewellsSql['image'] : '')
                    ->toArray();
            })->toArray();
            $jewellsData = $jewellsSql;

            $all_data = array(
                'bannerData' => $bannerData,
                'bannerPopUpData' => $bannerPopup,
                'clientsData' => $clients,
                'blogsData' => $blogs,
                'active_plan' => $activeplans,
                'our_services' => $ourServices,
                'jewellsData' => $jewellsData,
                'middleBannerData' => $middlebanner,
                'we_do_title' => $we_do_title,
                'we_do_info' => $we_do_info,
                'Isverify' => $Isverify,
            );

            return response()->json(['status' => true, 'message' => 'Get Dashboard data successfully.', 'data' => $all_data], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Plan list.
     */
    public function getPlanList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $page_type = $request->page_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }

            $plansPaginator = Plan::with([
                'PlanPurchase' => function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                }
            ])->where('status', 1)->where('page_type', $page_type)->where('is_deleted', 0)->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $plans = $plansPaginator->getCollection()->map(function ($plan) use ($base_url) {
                $plan = collect($plan)->except(['description', 'sqrt', 'employees', 'stock', 'validity', 'session', 'duration', 'on_call_support', 'month_duration', 'personal_meeting', 'deliveries', 'duration_year', 'cmd_visit', 'store_visit', 'module_ids', 'is_deleted', 'created_at', 'updated_at', 'plan_purchase'])
                    ->put('plan_image', $plan['image'] ? $base_url . $this->plan_path . $plan['image'] : '')
                    ->put('active_plan', $plan['PlanPurchase'] ? (string) $plan['PlanPurchase']->purchase_status : '')
                    ->toArray();
                // $plan['duration'] = (string) $plan['duration'];
                return $plan;
            });

            // Replace the original collection with the transformed one
            $plansPaginator->setCollection(collect($plans));

            // Get pagination metadata
            $pagination = [
                'total' => $plansPaginator->total(),
                'count' => $plansPaginator->count(),
                'per_page' => $plansPaginator->perPage(),
                'current_page' => $plansPaginator->currentPage(),
                'total_pages' => $plansPaginator->lastPage(),
            ];

            $plansData = [
                'pagination' => $pagination,
                'data' => $plans,
            ];

            return response()->json(['status' => true, 'message' => 'Get Plans data successfully.', 'data' => $plansData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Web Plan list Web.
     */
    public function getWebPlanList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_type = $request->plan_type;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            // if ($userData['status'] == false) {
            //     return $checkToken->getContent();
            // }

            $plansPaginator = Plan::with([
                'PlanPurchase' => function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                }
            ])->where('status', 1)->where('page_type', $plan_type)->where('is_deleted', 0)->paginate($this->per_page_show, ['*'], 'page', $page_number);
            // dd($plansPaginator);
            $plans = $plansPaginator->getCollection()->map(function ($plan) use ($base_url) {
                $plan = collect($plan)->except(['description', 'sqrt', 'employees', 'stock', 'validity', 'session', 'duration', 'on_call_support', 'month_duration', 'personal_meeting', 'deliveries', 'duration_year', 'cmd_visit', 'store_visit', 'module_ids', 'is_deleted', 'created_at', 'updated_at', 'plan_purchase'])
                    ->put('plan_image', $plan['image'] ? $base_url . $this->plan_path . $plan['image'] : '')
                    ->put('active_plan', $plan['PlanPurchase'] ? (string) $plan['PlanPurchase']->purchase_status : '')
                    ->toArray();
                // $plan['duration'] = (string) $plan['duration'];
                return $plan;
            });

            // Replace the original collection with the transformed one
            $plansPaginator->setCollection(collect($plans));

            // Get pagination metadata
            $pagination = [
                'total' => $plansPaginator->total(),
                'count' => $plansPaginator->count(),
                'per_page' => $plansPaginator->perPage(),
                'current_page' => $plansPaginator->currentPage(),
                'total_pages' => $plansPaginator->lastPage(),
            ];

            $plansData = [
                'pagination' => $pagination,
                'data' => $plans,
            ];

            return response()->json(['status' => true, 'message' => 'Get Plans data successfully.', 'data' => $plansData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Plan Details.
     */
    public function getPlanDetail(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            // if ($userData['status'] == false) {
            //     return $checkToken->getContent();
            // }
            $plansPaginator = Plan::with([
                'PlanPurchase' => function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                }
            ])->where('status', 1)->where('is_deleted', 0)->where('id', $plan_id)->get();
            $plans = $plansPaginator->map(function ($plan) use ($base_url) {
                $plan['duration'] = (string) $plan['duration'];
                return collect($plan)->except(['password', 'role_id', 'email_verified_at'])
                    ->put('plan_image', ($plan['image']) ? $base_url . $this->plan_path . $plan['image'] : '')
                    ->put('active_plan', $plan['PlanPurchase'] ? (string) $plan['PlanPurchase']->purchase_status : '')
                    ->toArray();
            })->toArray();

            $result = [];
            $finalData = [];

            foreach ($plans as $li) {
                $moduleIds = array_filter(explode(',', $li['module_ids'] ?? ''));
                // dd($li['page_type']);
                // Fetch service modules based on those IDs
                if ($li['page_type'] == 'mmb') {
                    $points = Service::select('id', 'title')->whereIn('id', $moduleIds)->get();
                    $modulesName = Modules::whereRaw("FIND_IN_SET(?, plan_type)", [$li['plan_type']])->get();
                    $result = [
                        'id' => $li['id'],
                        'plan_name' => $li['plan_name'],
                        'sub_plan_type' => $li['plan_type'],
                        'page_type' => $li['page_type'],
                        'plan_image' => $li['plan_image'],
                        'active_plan' => $li['active_plan'],
                        // 'personal_meeting' => $li['personal_meeting'],
                        // 'sort_desc' => $li['sort_desc'],
                        'price' => $li['price'],
                        'price_within_india' => $li['price_within_india'],
                        'price_within_gujrat' => $li['price_within_gujrat'],
                        'duration' => $li['validity'],
                        'personalization' => $li['personalization'],
                        'documents' => $li['documents'],
                        'no_of_moduls' => $modulesName->count(),
                        // 'session' => $li['session'],
                        // 'meeting_duration' => $li['duration'],
                        // 'month_duration' => $li['month_duration'],
                        // 'deliveries' => $li['deliveries'],
                        'tax' => $li['tax'],
                        'points' => $points,
                    ];
                } else if ($li['page_type'] == 'start-up') {
                    $points = StartupService::select('id', 'title')->get();
                    $result = [
                        'id' => $li['id'],
                        'plan_name' => $li['plan_name'],
                        'active_plan' => $li['active_plan'],
                        'sub_plan_type' => $li['plan_type'],
                        'page_type' => $li['page_type'],
                        'plan_image' => $li['plan_image'],
                        'sort_desc' => $li['sort_desc'],
                        'price' => $li['price'],
                        'price_within_india' => $li['price_within_india'],
                        'price_within_gujrat' => $li['price_within_gujrat'],
                        'sqrt' => $li['sqrt'],
                        'employees' => $li['employees'],
                        'stock' => $li['stock'],
                        'duration' => '100',
                        'tax' => $li['tax'],
                        'services' => $points,
                    ];
                } else if ($li['page_type'] == 'revision-batch') {
                    $activePlan = Plan::where('id', $plan_id)->first();
                    $ids = explode(',', $activePlan->module_ids);
                    $modulesName = Modules::select('id', 'name as title')->whereIn('id', $ids)->where('status', 1)->get();
                    $result = [
                        'id' => $li['id'],
                        'plan_name' => $li['plan_name'],
                        'active_plan' => $li['active_plan'],
                        'sub_plan_type' => $li['plan_type'],
                        'page_type' => $li['page_type'],
                        'plan_image' => $li['plan_image'],
                        'price' => $li['price'],
                        'points' => $modulesName,
                        'no_of_moduls' => $modulesName->count(),
                        'tax' => $li['tax'],
                    ];
                } else if ($li['page_type'] == 'idp') {
                    $points = Service::select('id', 'title')->whereIn('id', $moduleIds)->get();
                    $modulesName = Modules::whereRaw("FIND_IN_SET(?, plan_type)", [$li['plan_type']])->get();
                    $serviceModules = Service::select('id', 'title')->whereRaw("FIND_IN_SET(?, plan_type)", ['IDP'])->get();
                    // dd($points);
                    $merged = $points->merge($serviceModules);
                    $result = [
                        'id' => $li['id'],
                        'plan_name' => $li['plan_name'],
                        'sub_plan_type' => $li['plan_type'],
                        'page_type' => $li['page_type'],
                        'plan_image' => $li['plan_image'],
                        'active_plan' => $li['active_plan'],
                        'price' => $li['price'],
                        'price_within_india' => $li['price_within_india'],
                        'price_within_gujrat' => $li['price_within_gujrat'],
                        'duration' => $li['validity'],
                        'personalization' => $li['personalization'],
                        'documents' => $li['documents'],
                        'no_of_moduls' => $modulesName->count(),
                        'tax' => $li['tax'],
                        'data_analytics_dash' => $li['data_analytics_dash'],
                        'marketing_campaign_support' => $li['marketing_campaign_support'],
                        'on_call_support' => 'Unlimited',
                        'points' => $merged,
                    ];
                } else if ($li['page_type'] == 'stay-aware-live-renewal') {
                    $points = Service::select('id', 'title')->whereIn('id', $moduleIds)->get();
                    $serviceModules = Service::select('id', 'title')->whereRaw("FIND_IN_SET(?, plan_type)", ['Stay Aware'])->get();

                    $merged = $points->merge($serviceModules);
                    $result = [
                        'id' => $li['id'],
                        'plan_name' => $li['plan_name'],
                        'sub_plan_type' => $li['plan_type'],
                        'page_type' => $li['page_type'],
                        'plan_image' => $li['plan_image'],
                        'active_plan' => $li['active_plan'],
                        'price' => $li['price'],
                        'price_within_india' => $li['price_within_india'],
                        'price_within_gujrat' => $li['price_within_gujrat'],
                        'no_of_moduls' => 0,
                        'tax' => $li['tax'],
                        'on_call_support' => $li['on_call_support'],
                        'points' => $merged,
                    ];
                } else {
                    $points = [];
                    $result = [];
                }

                $finalData = $result;
            }

            return response()->json(['status' => true, 'message' => 'Get Plans data successfully.', 'data' => $finalData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Add On's list.
     */
    public function getAddOnsList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $blogsPaginator = Addon::with([
                'AddOnPurchased' => function ($query) use ($user_id, $plan_id) {
                    $query->where('user_id', $user_id);
                    $query->where('plan_id', $plan_id);
                }
            ])->where('status', 1)->where('is_deleted', 0)->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $blogs = $blogsPaginator->getCollection()->map(function ($blog) use ($base_url) {
                $blog['on_store_visit'] = (string) $blog['on_store_visit'];
                $blog = collect($blog)
                    ->put('active_service', ($blog['AddOnPurchased']) ? $blog['AddOnPurchased']->purchase_status : '')
                    ->toArray();
                return $blog;
            });

            // Replace the original collection with the transformed one
            $blogsPaginator->setCollection(collect($blogs));

            // Get pagination metadata
            $pagination = [
                'total' => $blogsPaginator->total(),
                'count' => $blogsPaginator->count(),
                'per_page' => $blogsPaginator->perPage(),
                'current_page' => $blogsPaginator->currentPage(),
                'total_pages' => $blogsPaginator->lastPage(),
            ];

            $blogsData = [
                'pagination' => $pagination,
                'data' => $blogs,
            ];

            return response()->json(['status' => true, 'message' => 'Get Add On data successfully.', 'data' => $blogsData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Add On's Detail.
     */
    public function getAddOnDetailsById(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $addon_id = $request->addon_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $blogsPaginator = Addon::where('status', 1)->where('is_deleted', 0)->where('id', $addon_id)->first();
            return response()->json(['status' => true, 'message' => 'Get Add On data successfully.', 'data' => $blogsPaginator], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Course list.
     */
    public function getCoursesList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $coursesPaginator = OurCourses::where('status', 1)->where('is_deleted', 0)->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $courses = $coursesPaginator->getCollection()->map(function ($course) use ($base_url) {
                $course = collect($course)
                    ->put('course_url', $course['video_url'] ? $base_url . $course['video_url'] : '')
                    ->toArray();
                return $course;
            });

            // Replace the original collection with the transformed one
            $coursesPaginator->setCollection(collect($courses));

            // Get pagination metadata
            $pagination = [
                'total' => $coursesPaginator->total(),
                'count' => $coursesPaginator->count(),
                'per_page' => $coursesPaginator->perPage(),
                'current_page' => $coursesPaginator->currentPage(),
                'total_pages' => $coursesPaginator->lastPage(),
            ];

            $coursesData = [
                'pagination' => $pagination,
                'data' => $courses,
            ];

            return response()->json(['status' => true, 'message' => 'Get Add On data successfully.', 'data' => $coursesData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get CMD Visit list.
     */
    public function getCMDVisitList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $servicedata = Service::where(['status' => 1, 'id' => env('CMD_VISIT_ID')])->where('is_deleted', 0)->first();
            $cmdvisitData = MemberModule::where([
                'membership_id' => $plan_id,
                'member_id' => $user_id,
                'module_id' => env('CMD_VISIT_ID')
            ])->orderBy('date', 'asc')->get(); // get all instead of paginate for fixed loop

            $finalVisits = [];

            for ($i = 0; $i < $servicedata->session; $i++) {
                $visit = $cmdvisitData[$i] ?? null; // get visit at index if exists

                $finalVisits[] = [
                    'module_status' => $visit ? ($visit->module_status ?? '') : 'Pending',
                    'date' => $visit ? (date(
                        'd M, Y',
                        strtotime($visit->date)
                    ) ?? '') : '-',
                    'time' => $visit ? (date(
                        'h:i A',
                        strtotime($visit->time)
                    ) ?? '') : '-',
                    'remarks' => $visit ? ($visit->remarks ?? '') : '-',
                ];
            }

            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully.', 'data' => $finalVisits], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get getStoreVisitList Visit list.
     */
    public function getStoreVisitList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $servicedata = Service::where(['status' => 1, 'id' => env('STORE_VISIT_ID')])->where('is_deleted', 0)->first();

            $cmdvisitData = MemberModule::where([
                'membership_id' => $plan_id,
                'member_id' => $user_id,
                'module_id' => env('STORE_VISIT_ID')
            ])->orderBy('date', 'asc')->get();

            $finalVisits = [];

            for ($i = 0; $i < $servicedata->session; $i++) {
                $visit = $cmdvisitData[$i] ?? null;

                $finalVisits[] = [
                    'module_status' => $visit ? ($visit->module_status ?? '') : 'Pending',
                    'date' => $visit ? (date(
                        'd M, Y',
                        strtotime($visit->date)
                    ) ?? '') : '-',
                    'time' => $visit ? (date(
                        'h:i A',
                        strtotime($visit->time)
                    ) ?? '') : '-',
                    'remarks' => $visit ? ($visit->remarks ?? '') : '-',
                ];
            }

            return response()->json(['status' => true, 'message' => 'Get store visit data successfully.', 'data' => $finalVisits], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get getModulesList Visit list.
     */
    public function getModulesList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $serviceData = Modules::where(['status' => 1, 'service_id' => 1])->where('is_deleted', 0)->get();
            $serviceCount = $serviceData->count();

            $finalVisits = [];
            // $servicedata = Modules::where(['status' => 1, 'service_id' => 1])->first();
            for ($i = 0; $i < $serviceCount; $i++) {
                $cmdvisitData = MemberModule::where([
                    'membership_id' => $plan_id,
                    'member_id' => $user_id,
                    'module_id' => $serviceData[$i]->id
                ])->orderBy('date', 'asc')->get();
                $visit = $cmdvisitData[$i] ?? null;

                $finalVisits[] = [
                    'module_name' => ($serviceData[$i]) ? $serviceData[$i]->name : '',
                    'module_status' => $visit ? ($visit->module_status ?? '') : 'Pending',
                    'date' => $visit ? (date(
                        'd M, Y',
                        strtotime($visit->date)
                    ) ?? '') : '-',
                    'time' => $visit ? (date(
                        'h:i A',
                        strtotime($visit->time)
                    ) ?? '') : '-',
                    'remarks' => $visit ? ($visit->remarks ?? '') : '-',
                ];
            }

            return response()->json(['status' => true, 'message' => 'Get store visit data successfully.', 'data' => $finalVisits], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get getRevisionBatchModules list.
     */
    public function getRevisionBatchModulesList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $plans = Plan::findOrFail(env('REVISION_BATCH_PLAN_ID'));

            $ids = explode(',', $plans->module_ids);
            $serviceData = Modules::whereIn('id', $ids)->where('status', 1)->get();
            // $serviceData = Modules::where(['status' => 1, 'service_id' => 1])->where('is_deleted', 0)->get();
            $serviceCount = $serviceData->count();

            $finalVisits = [];
            // $servicedata = Modules::where(['status' => 1, 'service_id' => 1])->first();
            for ($i = 0; $i < $serviceCount; $i++) {
                $cmdvisitData = MemberModule::where([
                    'membership_id' => $plan_id,
                    'member_id' => $user_id,
                    'module_id' => $serviceData[$i]->id
                ])->orderBy('date', 'asc')->get();
                $visit = $cmdvisitData[$i] ?? null;

                $finalVisits[] = [
                    'module_name' => ($serviceData[$i]) ? $serviceData[$i]->name : '',
                    'module_status' => $visit ? ($visit->module_status ?? '') : 'Pending',
                    'date' => $visit ? (date(
                        'd M, Y',
                        strtotime($visit->date)
                    ) ?? '') : '-',
                    'time' => $visit ? (date(
                        'h:i A',
                        strtotime($visit->time)
                    ) ?? '') : '-',
                    'remarks' => $visit ? ($visit->remarks ?? '') : '-',
                ];
            }

            return response()->json(['status' => true, 'message' => 'Get store visit data successfully.', 'data' => $finalVisits], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get getMeetingList Visit list.
     */
    public function getMeetingList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $servicedata = Service::where(['status' => 1, 'id' => env('MEETING_ID')])->where('is_deleted', 0)->first();

            $cmdvisitData = MemberModule::where([
                'membership_id' => $plan_id,
                'member_id' => $user_id,
                'module_id' => env('MEETING_ID')
            ])->orderBy('date', 'asc')->get();

            $finalVisits = [];

            for ($i = 0; $i < $servicedata->session; $i++) {
                $visit = $cmdvisitData[$i] ?? null;

                $finalVisits[] = [
                    'module_status' => $visit ? ($visit->module_status ?? '') : 'Pending',
                    'date' => $visit ? (date(
                        'd M, Y',
                        strtotime($visit->date)
                    ) ?? '') : '-',
                    'time' => $visit ? (date(
                        'h:i A',
                        strtotime($visit->time)
                    ) ?? '') : '-',
                    'remarks' => $visit ? ($visit->remarks ?? '') : '-',
                ];
            }

            return response()->json(['status' => true, 'message' => 'Get store visit data successfully.', 'data' => $finalVisits], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }


    /**
     * get Services list.
     */
    public function getServicesList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $servicesPaginator = Services::where('status', 1)->where('is_deleted', 0)->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $services = $servicesPaginator->getCollection()->map(function ($services) use ($base_url) {
                $services = collect($services)->except(['service_desc', 'created_at', 'updated_at', 'parent_id'])
                    ->put('service_image', $services['image'] ? $base_url . $this->visit_path . $services['image'] : '')
                    ->toArray();
                return $services;
            });

            // Replace the original collection with the transformed one
            $servicesPaginator->setCollection(collect($services));

            // Get pagination metadata
            $pagination = [
                'total' => $servicesPaginator->total(),
                'count' => $servicesPaginator->count(),
                'per_page' => $servicesPaginator->perPage(),
                'current_page' => $servicesPaginator->currentPage(),
                'total_pages' => $servicesPaginator->lastPage(),
            ];

            $coursesData = [
                'pagination' => $pagination,
                'data' => $services,
            ];

            return response()->json(['status' => true, 'message' => 'Get service list data successfully.', 'data' => $coursesData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Startup list.
     */
    public function getStartupList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $startupPaginator = Services::select('id', 'name', 'service_desc')
                ->where('is_deleted', 0)
                ->where('status', 1)
                ->where('parent_id', config('custome.STARTUP_ID'))
                ->get()->map(function ($item) {
                    $item->service_desc = strip_tags($item->service_desc); // Remove HTML tags
                    return $item;
                });

            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully.', 'data' => $startupPaginator], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong.', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Business list.
     */
    public function getBusinessList(Request $request)
    {
        try {
            $base_url = $this->base_url . '/';
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }

            $services = Services::select('id', 'name', 'service_desc', 'image')
                ->where('status', 1)
                ->where('parent_id', config('custome.BUSSINESS_ID'))
                ->where('is_deleted', 0)
                ->get();

            $formatted = $services->map(function ($service, $index) use ($base_url) {
                // Decode HTML entities and normalize various quote types
                $csvInput = html_entity_decode($service->service_desc, ENT_QUOTES | ENT_HTML5);
                $csvInput = str_replace(['&quot;', '&#34;', '“', '”'], '"', $csvInput);

                // Parse with str_getcsv to respect quoted values
                $rawFeatures = str_getcsv($csvInput);

                $features = collect($rawFeatures)
                    ->map(function ($desc) {
                        return trim(strip_tags($desc));
                    })
                    ->filter(function ($desc) {
                        return !empty($desc) && $desc !== '""' && $desc !== '"';
                    })
                    ->values()
                    ->map(function ($desc, $i) {
                        return [
                            'id' => $i + 1,
                            'name' => $desc,
                        ];
                    });

                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'image' => $service->image ? $base_url . 'services/' . $service->image : '',
                    'points' => $features,
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Business list fetched successfully.',
                'data' => $formatted
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }

    /**
     * get development business list.
     */
    public function getDevelopmentBusinessList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $title = Services::select('id', 'name')->where('status', 1)->where('is_deleted', 0)->where('id', 22)->where('parent_id', config('custome.DEVELOPMENT_BUSSINESS_ID'))->get();
            $startupPaginator = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('parent_id', config('custome.DEVELOPMENT_BUSSINESS_ID'))->where('is_deleted', 0)->get()->map(function ($service) {
                $service->image = url($this->service_path . $service->image);
                $service->service_desc = strip_tags($service->service_desc);
                return $service;
            });
            $final = ['title' => $title, 'detail' => $startupPaginator];
            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully.', 'data' => $final], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get HR list.
     */
    public function getHRList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $startupPaginator = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('parent_id', config('custome.HR_ID'))->where('is_deleted', 0)->get()->map(function ($service) {
                $service->image = url($this->service_path . $service->image);
                $service->service_desc = strip_tags($service->service_desc);
                return $service;
            });

            return response()->json(['status' => true, 'message' => 'Get Hr data successfully.', 'data' => $startupPaginator], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Jewellery Vidyapith list.
     */
    public function getJewelleryVidyapithList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $banner = Services::select('id', 'image')->where('status', 1)->where('is_deleted', 0)->where('id', 20)->where('parent_id', config('custome.VIDYAPITH_ID'))->orderBy('id', 'asc')->first();

            $info = Services::select('id', 'service_desc')->where('status', 1)->where('is_deleted', 0)->where('id', 15)->where('parent_id', config('custome.VIDYAPITH_ID'))->orderBy('id', 'asc')->first();

            if ($banner) {
                $banner->image = url($this->service_path . $banner->image); // Update the path as per your storage structure
            }

            $points = Services::select('sort_desc')->where('status', 1)->where('is_deleted', 0)->where('id', 21)->where('parent_id', config('custome.VIDYAPITH_ID'))->orderBy('id', 'asc')->get();
            $html = $points[0]['sort_desc'];

            $lis = explode(',', $html);
            $result = [];
            $id = 1;

            foreach ($lis as $li) {
                $result[] = [
                    'id' => $id,
                    'name' => trim($li),
                ];
                $id++;
            }

            $startupPaginator = Services::select('id', 'name', 'service_desc')->whereNotIn('id', [15])->where('status', 1)->where('parent_id', config('custome.VIDYAPITH_ID'))->where('is_deleted', 0)->orderBy('id', 'asc')->get();
            $detail = ['info' => strip_tags($info->service_desc), 'banner' => $banner->image, 'points' => $result, 'detail' => $startupPaginator];
            return response()->json(['status' => true, 'message' => 'Get Hr data successfully.', 'data' => $detail], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Headway IT list.
     */
    public function getHeadwayITList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $design = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('is_deleted', 0)->where('name', 'LIKE', '%Design%')->where('parent_id', config('custome.IT_ID'))->get()->map(function ($services) {
                $services->image = url($this->service_path . $services->image);
                $services->service_desc = strip_tags($services->service_desc);
                return $services;
            });

            $development = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('is_deleted', 0)->where('name', 'LIKE', '%Development%')->where('parent_id', config('custome.IT_ID'))->get()->map(function ($service) {
                $service->image = url($this->service_path . $service->image);
                $service->service_desc = strip_tags($service->service_desc);
                return $service;
            });

            $marketing = Services::select('id', 'name', 'service_desc', 'image')->where('is_deleted', 0)->where('status', 1)->where('name', 'LIKE', '%marketing%')->where('parent_id', config('custome.IT_ID'))->get()->map(function ($service) {
                $service->image = url($this->service_path . $service->image);
                $service->service_desc = strip_tags($service->service_desc);
                return $service;
            });

            $finalData = ['design' => $design, 'development' => $development, 'marketing' => $marketing];
            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully.', 'data' => $finalData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Headway Initiative list.
     */
    public function getHeadwayInitiativeList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $startupPaginator = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('parent_id', config('custome.HEADWAY_INITIATIVE_ID'))->where('is_deleted', 0)->get()->map(function ($service) {
                $service->image = url($this->service_path . $service->image);
                $service->service_desc = strip_tags($service->service_desc);
                return $service;
            });

            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully.', 'data' => $startupPaginator], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * contact us
     */
    public function contactUs(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            $validator = Validator::make($request->all(), [
                "name" => "required|regex:/^[a-z A-Z]+$/",
                "email" => "required|email:rfc,dns",
                "country_code" => "required",
                "phone" => "required|numeric|min:10|digits:10",
                "city" => "required"
            ]);

            if ($validator->fails()) {
                $result['status'] = false;
                $result['message'] = $validator->errors()->first();
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

            $result['status'] = true;
            $result['message'] = 'Your inquiry has been sent successfully, we will be contact you soon.';
            $result['data'] = Contact::create([
                "name" => $request->name,
                "email" => $request->email,
                "country_code" => $request->country_code,
                "phone" => $request->phone,
                "city" => $request->city,
                "message" => $request->message,
            ]);

            return response()->json($result, 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Video list.
     */
    public function getVideoList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $plan_id = $request->plan_id;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $blogsPaginator = Video::where('status', 1)->where('plan_id', $plan_id)->where('is_deleted', 0)->where('type', 'Youtube')->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $blogs = $blogsPaginator->getCollection()->map(function ($blog) use ($base_url) {
                $blog = collect($blog)
                    ->put('video_url', $blog['url'] ? $blog['url'] : '')
                    ->toArray();
                return $blog;
            });

            // Replace the original collection with the transformed one
            $blogsPaginator->setCollection(collect($blogs));

            // Get pagination metadata
            $pagination = [
                'total' => $blogsPaginator->total(),
                'count' => $blogsPaginator->count(),
                'per_page' => $blogsPaginator->perPage(),
                'current_page' => $blogsPaginator->currentPage(),
                'total_pages' => $blogsPaginator->lastPage(),
            ];

            $blogsData = [
                'pagination' => $pagination,
                'data' => $blogs,
            ];

            return response()->json(['status' => true, 'message' => 'Get Video data successfully.', 'data' => $blogsData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get training video list.
     */
    public function getTraningVideoList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $plan_id = $request->plan_id;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $blogsPaginator = Video::where('status', 1)->where('plan_id', $plan_id)->where('is_deleted', 0)->where('type', 'Training')->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $blogs = $blogsPaginator->getCollection()->map(function ($blog) use ($base_url) {
                $blog = collect($blog)
                    ->put('video_url', $blog['url'] ? $blog['url'] : '')
                    ->toArray();
                return $blog;
            });

            // Replace the original collection with the transformed one
            $blogsPaginator->setCollection(collect($blogs));

            // Get pagination metadata
            $pagination = [
                'total' => $blogsPaginator->total(),
                'count' => $blogsPaginator->count(),
                'per_page' => $blogsPaginator->perPage(),
                'current_page' => $blogsPaginator->currentPage(),
                'total_pages' => $blogsPaginator->lastPage(),
            ];

            $blogsData = [
                'pagination' => $pagination,
                'data' => $blogs,
            ];

            return response()->json(['status' => true, 'message' => 'Get Training Video data successfully.', 'data' => $blogsData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Blogs list.
     */
    public function getBlogsList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $blogsPaginator = Blog::where('status', 1)->where('is_deleted', 0)->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $blogs = $blogsPaginator->getCollection()->map(function ($blog) use ($base_url) {
                $blog = collect($blog)
                    ->put('blog_image', $blog['image'] ? $base_url . $this->blog_path . $blog['image'] : '')
                    ->put('share_url', $base_url . '/blog/' . Str::slug($blog->title))
                    ->toArray();
                return $blog;
            });

            // Replace the original collection with the transformed one
            $blogsPaginator->setCollection(collect($blogs));

            // Get pagination metadata
            $pagination = [
                'total' => $blogsPaginator->total(),
                'count' => $blogsPaginator->count(),
                'per_page' => $blogsPaginator->perPage(),
                'current_page' => $blogsPaginator->currentPage(),
                'total_pages' => $blogsPaginator->lastPage(),
            ];

            $blogsData = [
                'pagination' => $pagination,
                'data' => $blogs,
            ];

            return response()->json(['status' => true, 'message' => 'Get Blog data successfully.', 'data' => $blogsData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * Add/Update Notification Setting UserWise.
     */
    public function setNotificationUser(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $data = $request->only([
                'user_id',
                'email_notification_email',
                'seminar_notification_email',
                'promotional_notification_email',
                'subscription_notification_email',
                'news_updates_notification_email',
                'email_notification_push',
                'seminar_notification_push',
                'promotional_notification_push',
                'subscription_notification_push',
                'news_updates_notification_push'
            ]);

            $NotificationSetting = NotificationSetting::updateOrCreate(
                ['user_id' => $user_id], // Search condition
                $data // Data to update or insert
            );
            $NotificationSetting = NotificationSetting::where('user_id', $user_id)->where('is_deleted', 0)->first();
            return response()->json(['status' => true, 'message' => 'Notification settings saved successfully.', 'data' => $NotificationSetting], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * Get Notification Setting UserWise.
     */
    public function getNotificationUser(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }

            $NotificationSetting = NotificationSetting::where('user_id', $user_id)->where('is_deleted', 0)->first();
            if (!$NotificationSetting) {
                $NotificationSetting = [
                    "user_id" => $user_id,
                    "email_notification_email" => 1,
                    "seminar_notification_email" => 1,
                    "promotional_notification_email" => 1,
                    "subscription_notification_email" => 1,
                    "news_updates_notification_email" => 1,
                    "email_notification_push" => 1,
                    "seminar_notification_push" => 1,
                    "promotional_notification_push" => 1,
                    "subscription_notification_push" => 1,
                    "news_updates_notification_push" => 1
                ];
            }

            return response()->json(['status' => true, 'message' => 'Notification settings get successfully.', 'data' => $NotificationSetting], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * Membersip form.
     */
    public function membershipForm(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $validator = Validator::make($request->all(), [
                //reference
                'reference_by' => 'required|string',

                // Personal Information
                'full_name' => 'required|string|max:255',
                'gender' => 'required',
                'date_of_birth' => 'required|date|before:today',
                'qualification' => 'required|string|max:255',
                'occupation' => 'required|string|max:255',
                'designation' => 'required|string|max:255',
                'mobile_no' => 'required|digits:10',
                'email' => 'required|email',
                'date_of_anniversary' => 'nullable|date',
                'nationality' => 'required|string|max:255',

                // Address
                'address' => 'required|string|max:500',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'pincode' => 'required|digits:6',

                // Business Information
                'organization_name' => 'required|string|max:255',
                'bussiness_city' => 'required|string|max:255',
                'bussiness_state' => 'required|string|max:255',
                'bussiness_pincode' => 'required|digits:6',
                'bussiness_email' => 'required|email',
                'registered_office_address' => 'required|string|max:500',
                'gst_no' => 'required|regex:/^(\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1})$/',
                'pan_no' => 'required|regex:/^[A-Z]{5}\d{4}[A-Z]{1}$/',
                'date_of_incorporation' => 'required|date',
                'organization_type' => 'required|string|max:255',
                'business_ContactPerson_name' => 'required|string|max:255',
                'business_ContactPerson_mobile' => 'required|digits:10',

                //  Product Details
                'product_id' => 'required',
                'expectation_from_this_program' => 'max:1000',
                'total_price' => 'required',
                'payment_receipt' => 'required_if:cash_payment_request,0|nullable|mimes:jpg,jpeg,png,pdf|max:2048',
                'cash_payment_request' => 'required_if:payment_receipt,null|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => (object) [],
                ], 200);
            }
            $data = $request->all();
            $planData = [];
            if ($request->hasFile('payment_receipt')) {
                $file = $request->file('payment_receipt');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('payment_receipts'), $filename);
                $data['payment_receipt'] = $filename;
                $planData['payment_receipt'] = $filename;
            }
            $result = Membership::create($data);
            $planData['plan_id'] = $request->product_id;
            $planData['user_id'] = $user_id;
            $planData['total_price'] = $request->total_price;
            $result = PlanPurchase::create($planData);

            return response()->json(['status' => true, 'message' => 'Membership applied successfully.', 'data' => $result], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * Add On Purchase plan.
     */
    public function addOnPurchase(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'plan_id' => 'required',
                'addon_id' => 'required',
                'payment_receipt' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()->first(),
                ], 422);
            }
            $data = $request->all();
            if ($request->hasFile('payment_receipt')) {
                $file = $request->file('payment_receipt');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('addon_receipts'), $filename);
                $data['payment_receipt'] = $filename;
            }
            $result = AddOnPurchase::create($data);

            return response()->json(['status' => true, 'message' => 'Plan purchase successfully.', 'data' => $result], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Privacy Policy data.
     */
    public function getPrivacyPolicy(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $privacypolicy = Cms::where('status', 1)->where('is_deleted', 0)->where('id', env('PRIVACY_POLICY_ID'))->first();

            return response()->json(['status' => true, 'message' => 'Get Privacy Policy data successfully.', 'data' => $privacypolicy], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Web Privacy data.
     */
    public function getWebPolicy(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $privacypolicy = Cms::where('status', 1)->where('plan_id', $plan_id)->where('is_deleted', 0)->whereIn('id', [env('WEB_POLICY_ID'), 14])->first();
            if ($privacypolicy) {
                return response()->json(['status' => true, 'message' => 'Get Web Policy data successfully.', 'data' => $privacypolicy], 200);
            } else {
                return response()->json(['status' => true, 'message' => 'Get Web Policy data successfully.', 'data' => (object) []], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Web Site Privacy data.
     */
    public function getWebSitePolicy(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $privacypolicy = Cms::where('status', 1)->where('plan_id', $plan_id)->where('is_deleted', 0)->whereIn('id', [env('WEBSITE_POLICY_ID'), 13])->first();
            if ($privacypolicy) {
                return response()->json(['status' => true, 'message' => 'Get Web Site Policy data successfully.', 'data' => $privacypolicy], 200);
            } else {
                return response()->json(['status' => true, 'message' => 'Get Web Site Policy data successfully', 'data' => (object) []], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get AboutUs data.
     */
    public function getAboutUs(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $privacypolicy = Cms::where('status', 1)->where('id', env('ABOUTUS_ID'))->where('is_deleted', 0)->first();

            return response()->json(['status' => true, 'message' => 'Get About Us data successfully.', 'data' => $privacypolicy], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Faqs data.
     */
    public function getFaqsData(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $privacypolicy = Cms::where('status', 1)->where('id', env('FAQS_ID'))->where('is_deleted', 0)->first();

            return response()->json(['status' => true, 'message' => 'Get FAQ data successfully.', 'data' => $privacypolicy], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Strategy Graphics data.
     */
    public function getStrategyGraphics(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $privacypolicy = Cms::where('status', 1)->whereIn('id', [env('STRATEGY_GRAPHICS_ID'), 15])->where('is_deleted', 0)->where('plan_id', $plan_id)->first();

            return response()->json(['status' => true, 'message' => 'Get strategy and Structure data successfully.', 'data' => $privacypolicy], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Privacy data.
     */
    public function getDataConfig(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $page_number = $request->page;

            $paymentHeadway = ['account_name' => 'Headway Business Solution LLP ', 'bank_name' => 'Axis Bank', 'account_no' => '917020044782976', 'ifsc_code' => 'UTIB0001064', 'GST' => '24AAKF3737P1ZW'];

            $material_provided = 'Headway Business Bag, Note Book Head, Pen 1 Business Kit Include Monthly, Topics Programs Schedule Business Material';

            $allData['paymentData'] = $paymentHeadway;
            $allData['material_provided'] = $material_provided;

            return response()->json(['status' => true, 'message' => 'Get data successfully.', 'data' => $allData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Terms & Condition data.
     */
    public function getTermsCondition(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $blogsPaginator = Cms::where('status', 1)->where('id', 3)->where('is_deleted', 0)->first();

            return response()->json(['status' => true, 'message' => 'Get Privacy Policy data successfully.', 'data' => $blogsPaginator], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get getMembershipPolicy data.
     */
    public function getMembershipPolicy(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $blogsPaginator = Cms::where('status', 1)->where('id', 8)->where('is_deleted', 0)->first();
            if ($blogsPaginator) {
                return response()->json(['status' => true, 'message' => 'Get Membership Policy data successfully.', 'data' => $blogsPaginator], 200);
            } else {
                return response()->json(['status' => true, 'message' => 'Get Data Processing Support data successfully..', 'data' => (object) []], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get get Data Processing Support data.
     */
    public function getDataProcessingSupport(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $plan_id = $request->plan_id;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $blogsPaginator = Cms::where('status', 1)->whereIn('id', [9, 11])->where('is_deleted', 0)->where('plan_id', $plan_id)->first();
            if ($blogsPaginator) {
                return response()->json(['status' => true, 'message' => 'Get Data Processing Support data successfully.', 'data' => $blogsPaginator], 200);
            } else {
                return response()->json(['status' => true, 'message' => 'Get Data Processing Support data successfully..', 'data' => (object) []], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Marketing Strategy data.
     */
    public function getMarketingStrategy(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $plan_id = $request->plan_id;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $blogsPaginator = Cms::where('status', 1)->whereIn('id', [10, 12])->where('is_deleted', 0)->where('plan_id', $plan_id)->first();
            if ($blogsPaginator) {
                return response()->json(['status' => true, 'message' => 'Get Data Marketing Strategy data successfully.', 'data' => $blogsPaginator], 200);
            } else {
                return response()->json(['status' => true, 'message' => 'Get Data Processing Support data successfully..', 'data' => (object) []], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * Plan Purchase.
     */
    public function planPurchase(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $addon_id = $request->addon_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
                'plan_id' => 'required',
                'addon_id' => 'required',
                'total_amount' => 'required',
            ]);

            if ($validator->fails()) {
                $result['status'] = false;
                $result['message'] = $validator->errors()->first();
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

            $blogsPaginator = PlanPurchase::create([
                'user_id' => $user_id,
                'plan_id' => $plan_id,
                'addon_id' => $addon_id,
                'total_amount' => $request->total_amount,
            ]);

            return response()->json(['status' => true, 'message' => 'Get Add On data successfully.', 'data' => $blogsPaginator], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Account information.
     */
    public function getAccountInfo(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                $result['status'] = false;
                $result['message'] = $validator->errors()->first();
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

            $userData = User::where('id', $user_id)->first();
            $batchData = MemberBatch::where('member_id', $user_id)->first();
            $userDataInfo['headwayData'] = ['headway_id' => ($batchData->headway_id) ?? '-', 'batch' => ($batchData->batch) ?? '-'];
            $userDataInfo['personal_info'] = ['name' => $userData->name, 'email' => $userData->email, 'phone_no' => $userData->phone_number, 'alternate_phone' => $userData->alternate_phone];
            $userDataInfo['address'] = ['address' => $userData->flat_no, 'area' => $userData->area, 'city' => $userData->city, 'state' => $userData->state, 'pincode' => $userData->pincode];

            return response()->json(['status' => true, 'message' => 'Account Information data successfully.', 'data' => $userDataInfo], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Account information.
     */
    public function getMyPlan(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $plan_id = $request->plan_id;
            $page_type = $request->page_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                $result['status'] = false;
                $result['message'] = $validator->errors()->first();
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

            $userData = User::where('id', $user_id)->first();
            if ($plan_id == env('REVISION_BATCH_PLAN_ID')) {
                $activePlan = Plan::where('id', $plan_id)->first();
            } else if ($plan_id == env('STAY_AWARE_ID')) {
                $activePlan = Plan::where('id', $plan_id)->first();
            } else {
                $activePlan = PlanPurchase::with('Plans')->where('user_id', $user_id)->where('plan_id', $plan_id)->first();
            }
            // dd($activePlan['page_type']);
            $userDataInfo = [];
            if ($activePlan) {
                $moduleIds = ($activePlan->Plans) ? explode(',', $activePlan->Plans->module_ids) : $activePlan->module_ids;

                $add_on_services = AddOnPurchase::with('AddOnService')->where('status', 1)
                    ->whereHas('AddOnService', function ($query) {
                        $query->where('purchase_status', 'Approved');
                    })->where('user_id', $user_id)->get();
                $mod = [];
                $allStartupServices = [];
                $allRevisionServices = [];
                if ($page_type == 'start-up' || $page_type == 'mmb') { // For startup batch
                    $modulesName = Modules::whereRaw("FIND_IN_SET(?, plan_type)", [$activePlan->Plans->plan_type])->get();
                    if ($moduleIds) {
                        $serviceName = StartupServiceModules::where(['status' => 1])->get();
                        $dataGetNodules = MemberStartupModule::where('member_id', $user_id)->get();
                        foreach ($serviceName as $key => $value) {
                            $mod = $dataGetNodules->where('startup_id', $value['id'])->first();
                            $services['service_name'] = $value['title'];
                            $services['service_id'] = $value['id'];
                            $services['date'] = $mod ? $mod->date : '';
                            $services['time'] = $mod ? $mod->time : '';
                            $services['status'] = $mod ? $mod->module_status : 'Pending';
                            $services['remarks'] = $mod ? $mod->remarks : '';
                            $allStartupServices[] = $services;
                        }
                    }
                    $userDataInfo['plan_name'] = ($activePlan->Plans) ? $activePlan->Plans->plan_name : '';
                    $userDataInfo['purchased_price'] = ($activePlan->total_price) ? $activePlan->total_price : '';
                    $userDataInfo['tax'] = ($activePlan->Plans) ? $activePlan->Plans->tax : '';
                    $userDataInfo['documents'] = ($activePlan->Plans) ? $activePlan->Plans->documents : '';
                    $userDataInfo['personalization'] = ($activePlan->Plans) ? $activePlan->Plans->personalization : '';
                    $userDataInfo['duration'] = ($activePlan->Plans) ? $activePlan->Plans->duration : '';
                    $userDataInfo['no_of_modules'] = $modulesName->count();
                    $userDataInfo['sub_plan_type'] = ($activePlan->Plans) ? $activePlan->Plans->plan_type : '';
                    $userDataInfo['plan_id'] = ($activePlan->Plans) ? $activePlan->Plans->id : '';
                    $userDataInfo['sqrt'] = ($activePlan->Plans) ? $activePlan->Plans->sqrt : '';
                    $userDataInfo['employees'] = ($activePlan->Plans) ? $activePlan->Plans->employees : '';
                    $userDataInfo['stock'] = ($activePlan->Plans) ? $activePlan->Plans->stock : '';
                    $userDataInfo['duration'] = '100';
                    $userDataInfo['plan_icon'] = ($activePlan->Plans) ? $base_url . $this->plan_path . $activePlan->Plans->image : '';
                    $userDataInfo['start_up_points_arr'] = $allStartupServices;
                } else if ($activePlan['page_type'] == 'stay-aware-live-renewal') {
                    if ($moduleIds) {
                        //revision batch
                        $ids = explode(',', $moduleIds);
                        $modulesName = Modules::whereIn('id', $ids)->where('status', 1)->get();
                        $dataGetNodules = MemberModule::where('member_id', $user_id)->where('membership_id', env('REVISION_BATCH_PLAN_ID'))->get();
                        foreach ($modulesName as $key => $value) {
                            $mod = $dataGetNodules->where('module_id', $value['id'])->first();
                            $services['module_name'] = $value['name'];
                            $services['service_id'] = $value['id'];
                            $services['date'] = $mod ? $mod->date : '';
                            $services['time'] = $mod ? $mod->time : '';
                            $services['module_status'] = $mod ? $mod->module_status : 'Pending';
                            $services['remarks'] = $mod ? $mod->remarks : '';
                            $allStartupServices[] = $services;
                        }
                    }
                    $userDataInfo['plan_name'] = ($activePlan) ? $activePlan->plan_name : '';
                    $userDataInfo['plan_id'] = ($activePlan) ? $activePlan->id : '';
                    $userDataInfo['purchased_price'] = ($activePlan->price) ? $activePlan->price : '';
                    $userDataInfo['tax'] = ($activePlan) ? $activePlan->tax : '';
                    $userDataInfo['plan_icon'] = ($activePlan) ? $base_url . $this->plan_path . $activePlan->image : '';
                    $userDataInfo['revision_points_arr'] = $allStartupServices;
                } else { // For revision batch

                    if ($moduleIds) {
                        //revision batch
                        $ids = explode(',', $moduleIds);
                        $modulesName = Modules::whereIn('id', $ids)->where('status', 1)->get();
                        $dataGetNodules = MemberModule::where('member_id', $user_id)->where('membership_id', env('REVISION_BATCH_PLAN_ID'))->get();
                        foreach ($modulesName as $key => $value) {
                            $mod = $dataGetNodules->where('module_id', $value['id'])->first();
                            $services['module_name'] = $value['name'];
                            $services['service_id'] = $value['id'];
                            $services['date'] = $mod ? $mod->date : '';
                            $services['time'] = $mod ? $mod->time : '';
                            $services['module_status'] = $mod ? $mod->module_status : 'Pending';
                            $services['remarks'] = $mod ? $mod->remarks : '';
                            $allStartupServices[] = $services;
                        }
                    }
                    $userDataInfo['plan_name'] = ($activePlan) ? $activePlan->plan_name : '';
                    $userDataInfo['plan_id'] = ($activePlan) ? $activePlan->id : '';
                    $userDataInfo['purchased_price'] = ($activePlan->price) ? $activePlan->price : '';
                    $userDataInfo['tax'] = ($activePlan) ? $activePlan->tax : '';
                    $userDataInfo['plan_icon'] = ($activePlan) ? $base_url . $this->plan_path . $activePlan->image : '';
                    $userDataInfo['revision_points_arr'] = $allStartupServices;
                }
            }
            return response()->json(['status' => true, 'message' => 'My Plan Information data successfully.', 'data' => $userDataInfo], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * Logout functionality
     */
    public function logout(Request $request)
    {
        $token = $request->header('Authorization');
        $user = User::where('id', $request->user_id)->where('status', '1')->first();

        $userDevice = UserDevices::where('user_id', $request->user_id)->where('login_token', $token)->where('status', '1')->first();
        if ($userDevice) {
            $userDevice->device_token = '';
            $userDevice->status = '0';
            $userDevice->updated_at = date("Y-m-d H:i:s");
            $userDevice->save();
        }

        DB::table('user_devices')
            ->join("users", "user_devices.user_id", "=", "users.id")
            ->where("user_devices.login_token", "=", $token)
            ->where("user_devices.user_id", "=", $request->user_id)
            ->update(["user_devices.status" => '0', "user_devices.updated_at" => date("Y-m-d H:i:s"), 'user_devices.device_token' => '']);

        $result['status'] = true;
        $result['message'] = "Logout Successfully!";
        $result['data'] = (object) [];

        return response()->json($result, status: 200);
    }

    public function tokenVerify($token)
    {

        if ($token && Str::startsWith($token, 'Bearer ')) {
            $token = Str::replaceFirst('Bearer ', '', $token);
        }
        $user = DB::table('user_devices')
            ->where('user_devices.login_token', '=', $token)
            ->where('user_devices.status', '=', 1)
            ->count();
        if ($user == '' || $user == null || $user == 0) {
            $result['status'] = false;
            $result['message'] = "Token given is invalid, Please login again.";
            $result['data'] = [];
            return response()->json($result, 200);
        } else {
            $result['status'] = true;
            return response()->json($result, 200);
        }
    }

    public function getDashboardDataV2()
    {
        try {
            $base_url = $this->base_url;
            if (substr($base_url, -1) !== '/') {
                $base_url .= '/';
            }

            // 1. Banners
            $banners = Banner::where('status', 1)
                ->where('is_deleted', 0)
                ->whereNotIn('is_popup', [1])
                ->get()
                ->map(function ($banner) use ($base_url) {
                    return [
                        'id' => $banner->id,
                        'title' => $banner->title,
                        'heading' => $banner->heading,
                        'desc' => $banner->desc,
                        'image' => $banner->image ? $base_url . 'banners/' . $banner->image : '',
                        'is_popup' => $banner->is_popup,
                    ];
                });



            // 2. Client Logos (no description = logo)
            // $clientLogo = Client::where('status', 1)
            //     ->where('is_featured', 1)
            //     ->where('is_deleted', 0)
            //     ->get()
            //     ->map(function ($logo) use ($base_url) {
            //         return [
            //             'id' => $logo->id,
            //             'name' => $logo->name,
            //             'logo_image' => $logo->image ? $base_url . 'clients/' . $logo->image : '',
            //             'is_featured' => $logo->is_featured,
            //         ];
            //     });

            // 3. Our Clients (description present = our client)
            $ourClient = Client::where('status', 1)
                ->where('is_featured', 0)
                ->where('is_deleted', 0)
                ->get()
                ->map(function ($client) use ($base_url) {
                    return [
                        'id' => $client->id,
                        'title' => $client->name,
                        'description' => $client->city,
                        'client_logo' => $client->image ? $base_url . 'clients/' . $client->image : '',
                        'is_featured' => $client->is_featured,
                    ];
                });

            // 4. Get In Touch
            $getInTouch = Cms::where('status', 1)
                ->where('page_name', 'Marketing Strategy')
                ->first();

            // 5. Banner Popup
            $bannerPopup = Banner::where('is_popup', 1)->where('status', 1)->where('is_deleted', 0)
                ->first();

            $genSettings = DB::table('settings')
                ->get()->mapWithKeys(function ($item) {
                    return [$item->name => $item->value]; // creates key-value pairs
                });
            $bannerPopupUrl = $bannerPopup && $bannerPopup->image
                ? $base_url . 'banners/' . $bannerPopup->image
                : '';

            $dashboardData = [
                'banners' => $banners,
                // 'client_logo' => $clientLogo,
                'our_client' => $ourClient,
                'get_in_touch' => $getInTouch,
                'bannerPopup' => $bannerPopupUrl,
                'settings' => [
                    'address' => $genSettings['address'] ?? '',
                    'website' => $genSettings['web_url'] ?? '',
                    'mobile' => $genSettings['mobile'] ?? '',
                    'email' => $genSettings['email'] ?? '',
                    'happy_client' => $genSettings['happy_client'] ?? 0,
                    'years_of_experience' => $genSettings['years_of_experience'] ?? 0,
                    'our_location' => $genSettings['our_location'] ?? '',
                    'awards' => $genSettings['awards'] ?? 0,
                ]
            ];

            return response()->json([
                'status' => true,
                'message' => 'Get Dashboard data successfully.',
                'data' => $dashboardData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }

    public function getGeneralSettings()
    {
        try {
            $base_url = $this->base_url;
            if (substr($base_url, -1) !== '/') {
                $base_url .= '/';
            }

            $settings = DB::table('settings')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->name => $item->value]; // creates key-value pairs
                });

            return response()->json([
                'status' => true,
                'message' => 'General settings fetched successfully',
                'data' => [
                    'address' => $settings['address'] ?? '',
                    'website' => $settings['web_url'] ?? '',
                    'mobile' => $settings['mobile'] ?? '',
                    'email' => $settings['email'] ?? '',
                    'happy_client' => $settings['happy_client'] ?? 0,
                    'years_of_experience' => $settings['years_of_experience'] ?? 0,
                    'our_location' => $settings['our_location'] ?? '',
                    'awards' => $settings['awards'] ?? 0,
                    'instagram' => $settings['instagram'] ?? '',
                    'facebook' => $settings['facebook'] ?? '',
                    'linkedin' => $settings['linkedin'] ?? '',
                    'youtube' => $settings['youtube'] ?? '',
                    'twitter' => $settings['twitter'] ?? '',
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }

    public function getAboutUsV2()
    {
        try {
            // Fetch 'About Us' CMS entry
            // $aboutUs = Cms::where('status', 1)
            //     ->where('is_deleted', 0)
            //     ->where('page_name', 'About-us')
            //     ->first();

            // Fetch YouTube Link CMS entry
            $youtubeLink = Cms::where('status', 1)
                ->where('is_deleted', 0)
                ->where('page_name', 'about us youtube link')
                ->first();

            $data = [
                // 'about_us' => $aboutUs,
                'youtube_link' => $youtubeLink->description ?? '',
            ];

            return response()->json([
                'status' => true,
                'message' => 'Record list successfully',
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }

    public function getAboutStartupV2()
    {
        $base_url = $this->base_url;
        try {

            // OSS Galleries: from banners table where title is 'oss_gallery'
            // $ossGalleries = DB::table('testimonials')
            //     ->where('status', 1)
            //     ->where('is_deleted', 0)
            //     ->get(['id', 'title', 'image', 'description as desc'])
            //     ->map(function ($item) use ($base_url) {
            //         return [
            //             'id' => $item->id,
            //             'title' => $item->title,
            //             'image' => $base_url . '/testimonials/' . $item->image,
            //             'desc' => $item->desc,
            //         ];
            //     });

            $ossGalleries = DB::table('oss_galleries')
                ->get(['id', 'title', 'images'])
                ->map(function ($item) use ($base_url) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'image' => $base_url . '/oss_gallery/' . $item->images
                    ];
                });

            // About the Startup: from cms where page_name is 'about_startup'
            // $aboutStartup = Cms::where('page_name', 'About-us')
            //     ->where('status', 1)
            //     ->where('is_deleted', 0)
            //     ->get(['id', 'plan_id as title', 'description']);

            $aboutStartup = DB::table('about_startups')
                ->get(['id', 'title', 'description'])
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'description' => $item->description,
                    ];
                });

            // Client Testimonials
            // $clientTestimonials = Client::where('status', 1)
            //     ->where('is_deleted', 0)
            //     ->get(['id', 'image', 'name', 'city as location', 'description as comment']);

            // $clientTestimonials = DB::table('review_ratings')
            //     ->where('status', 'active')
            //     ->get();

            $brochure = DB::table('settings')
                ->where('name', 'All in one Brochure')
                ->first();

            $brochureUrl = $brochure ? $base_url . '/' . $brochure->value : '';
            // $clientTestimonials = DB::table('clients')
            //     ->where('status', 1)
            //     ->where('is_deleted', 0)
            //     ->get(['id', 'name', 'image', 'description as comment', 'city as location'])
            //     ->map(function ($item) use ($base_url) {
            //         return [
            //             'id' => $item->id,
            //             'name' => $item->name,
            //             'image' => $base_url . '/clients/' . $item->image,
            //             'location' => $item->location,
            //             'comment' => $item->comment,
            //         ];
            //     });
            $clientTestimonials = DB::table('testimonials')
                ->where('status', 1)
                ->where('type', 'startup')
                ->where('is_deleted', 0)
                ->get(['id', 'title as  name', 'image', 'description as comment', 'city as location', 'rating', 'shop_name', 'type'])
                ->map(function ($item) use ($base_url) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'shop_name' => $item->shop_name,
                        'image' => $base_url . '/testimonials/' . $item->image,
                        'location' => $item->location,
                        'comment' => $item->comment,
                        'type' => $item->type,
                        'rating' => $item->rating ? (float) $item->rating : 0, // Ensure rating is an integer
                    ];
                });



            $data = [
                'oss_galleries' => $ossGalleries,
                'about_the_startup' => $aboutStartup,
                'what_client_say_about_us' => $clientTestimonials,
                'brochure' => $brochureUrl,
            ];

            return response()->json([
                'status' => true,
                'message' => 'Record list successfully',
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }


    public function getMmbGallaries()
    {
        $base_url = $this->base_url . '/';
        try {
            $galleries = DB::table('mmb_galleries')
                ->get(['id', 'title', 'images'])
                ->map(function ($item) use ($base_url) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'image' => $base_url . 'mmb_gallery/' . $item->images
                    ];
                });

            $brochure = DB::table('settings')
                ->whereRaw('LOWER(name) LIKE ?', ['%all iN one brochure%'])
                ->first();

            $services = Services::select('id', 'name', 'service_desc', 'image')
                ->where('status', 1)
                ->where('parent_id', config('custome.BUSSINESS_ID'))
                ->where('is_deleted', 0)
                ->get();
            $formatted = $services->map(function ($service, $index) {
                // Step 1: Decode HTML entities and normalize quotes
                $csvInput = html_entity_decode($service->service_desc, ENT_QUOTES | ENT_HTML5);

                // Step 2: Normalize various quote types to standard double quotes
                $csvInput = str_replace(['&quot;', '&#34;', '“', '”'], '"', $csvInput);

                // Step 3: Parse using str_getcsv which respects quoted strings
                $rawFeatures = str_getcsv($csvInput);

                $features = collect($rawFeatures)
                    ->map(function ($desc) {
                        // Remove any HTML tags, but preserve quoted content
                        return trim(strip_tags($desc));
                    })
                    ->filter(function ($desc) {
                        return !empty($desc) && $desc !== '""' && $desc !== '"';
                    })
                    ->values()
                    ->map(function ($desc, $i) {
                        return [
                            'id' => $i + 1,
                            'feature' => $desc,
                        ];
                    });

                return [
                    'id' => $service->id,
                    'title' => $service->name,
                    'imgUrl' => $service->image ? $this->base_url . '/services/' . $service->image : '',
                    'features' => $features,
                ];
            });



            $brochureUrl = $brochure ? $base_url . $brochure->value : '';

            return response()->json([
                'status' => true,
                'message' => 'MMB Galleries fetched successfully',
                'data' => [
                    'mmb_services' => $formatted,
                    'galleries' => $galleries,
                    'brochure' => $brochureUrl
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }

    public function getServicePlanList()
    {
        $base_url = $this->base_url . '/';
        try {
            $servicePlans = DB::table('plans')
                ->where('status', 1)
                ->get(['id', 'plan_name as name', 'description as service_desc', 'price', 'image', 'duration as hours'])
                ->map(function ($item) use ($base_url) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'service_desc' => $item->service_desc,
                        'price' => $item->price,
                        'hours' => $item->hours,
                        'image' => $base_url . 'plans/' . $item->image
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Service plans fetched successfully',
                'data' => $servicePlans
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }

    public function getSsuGallaries()
    {
        $base_url = $this->base_url . '/';
        try {
            $galleries = DB::table('ssu_galleries')
                ->get(['id', 'title', 'images'])
                ->map(function ($item) use ($base_url) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'image' => $base_url . 'ssu_gallery/' . $item->images
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'SSU Galleries fetched successfully',
                'data' => $galleries
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }
    public function getGenGallaries(Request $request)
    {
        $base_url = $this->base_url . '/';
        $perPage = $request->get('per_page', 10); // Default 10 items per page

        try {
            $paginator = DB::table('gen_galleries')
                ->select('id', 'title', 'images')
                ->orderByDesc('id')
                ->paginate($perPage);

            // Transform the data
            $galleries = $paginator->getCollection()->map(function ($item) use ($base_url) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'image' => $base_url . 'gen_gallery/' . $item->images
                ];
            });

            // Replace the collection with transformed data
            $paginator->setCollection($galleries);

            return response()->json([
                'status' => true,
                'message' => 'Gen Galleries fetched successfully',
                'data' => [
                    'items' => $paginator->items(),
                    'current_page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                    'last_page' => $paginator->lastPage()
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function getBlogsListV2()
    {
        try {
            $base_url = $this->base_url;
            if (substr($base_url, -1) !== '/') {
                $base_url .= '/';
            }

            $blogs = Blog::where('blogs.status', 1)
                ->where('blogs.is_deleted', 0)
                ->leftJoin('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                ->orderBy('blogs.created_at', 'desc')
                ->get([
                    'blogs.id',
                    'blog_categories.name as category',
                    'blogs.author as name',
                    'blogs.blog_date',
                    'blogs.title',
                    'blogs.image',
                    'blogs.description',
                ])
                ->map(function ($blog) use ($base_url) {
                    return [
                        'id' => $blog->id,
                        'category' => $blog->category,
                        'name' => $blog->name,
                        'blog_date' => $blog->blog_date,
                        'title' => $blog->title,
                        'image' => $base_url . 'blogs/' . $blog->image,
                        'slug_url' => Str::slug($blog->title),
                        'description' => $blog->description,
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Blogs list fetched successfully',
                'data' => $blogs
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }

    public function getBlogDetails()
    {
        try {
            $blogId = request()->blog_id;

            if (!$blogId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Missing blog_id parameter',
                    'data' => (object) []
                ], 400);
            }

            $base_url = $this->base_url . '/';

            $blog = Blog::where('blogs.status', 1)
                ->where('blogs.is_deleted', 0)
                ->where('blogs.id', $blogId)
                ->leftJoin('blog_categories', 'blogs.category_id', '=', 'blog_categories.id')
                ->first([
                    'blogs.id',
                    'blog_categories.name as category',
                    'blogs.author as name',
                    'blogs.blog_date',
                    'blogs.title',
                    'blogs.image',
                    'blogs.description',
                ]);

            if (!$blog) {
                return response()->json([
                    'status' => false,
                    'message' => 'Blog not found',
                    'data' => (object) []
                ], 404);
            }

            $data = [
                'id' => $blog->id,
                'category' => $blog->category,
                'name' => $blog->name,
                'blog_date' => $blog->blog_date,
                'title' => $blog->title,
                'image' => $base_url . 'blogs/' . $blog->image,
                'share_url' => '#/blog/' . Str::slug($blog->title),
                'description' => $blog->description,
                'slug_url' => Str::slug($blog->title),
            ];

            return response()->json([
                'status' => true,
                'message' => 'Blog details fetched successfully',
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }


    public function getMeetOurTeam()
    {
        try {
            $base_url = $this->base_url . '/';


            $founders = Team::where('dept', 'founder')->where('status', 1)
                ->where('is_deleted', 0)
                ->orderBy('displayOrder', 'asc')
                ->get(['name', 'image', 'position'])
                ->map(function ($team) use ($base_url) {
                    return [
                        'name' => $team->name,
                        'image' => $base_url . 'teams/' . $team->image,
                        'position' => $team->position,
                    ];
                });

            $admins = Team::where('dept', 'admin')->where('status', 1)
                ->where('is_deleted', 0)
                ->orderBy('displayOrder', 'asc')
                ->get(['name', 'image', 'position'])
                ->map(function ($team) use ($base_url) {
                    return [
                        'name' => $team->name,
                        'image' => $base_url . 'teams/' . $team->image,
                        'position' => $team->position,
                    ];
                });

            $trainers = Team::where('dept', 'trainer')->where('status', 1)
                ->where('is_deleted', 0)
                ->orderBy('displayOrder', 'asc')
                ->get(['name', 'image', 'position'])
                ->map(function ($team) use ($base_url) {
                    return [
                        'name' => $team->name,
                        'image' => $base_url . 'teams/' . $team->image,
                        'position' => $team->position,
                    ];
                });

            $itteam = Team::where('dept', 'it')->where('status', 1)
                ->where('is_deleted', 0)
                ->orderBy('displayOrder', 'asc')
                ->get(['name', 'image', 'position'])
                ->map(function ($team) use ($base_url) {
                    return [
                        'name' => $team->name,
                        'image' => $base_url . 'teams/' . $team->image,
                        'position' => $team->position,
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Record list successfully',
                'data' => [
                    ['title' => 'Founders', 'employee' => $founders],
                    ['title' => 'Administration', 'employee' => $admins],
                    ['title' => 'Training Executive', 'employee' => $trainers],
                    ['title' => 'IT Experts', 'employee' => $itteam]
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }


    public function getUserAddOnService()
    {
        try {
            $addOnServices = AddOn::where('status', 1)
                ->where('is_deleted', 0)
                ->get(['id', 'title', 'sort_desc', 'description', 'price', 'created_at', 'updated_at'])
                ->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'title' => $service->title,
                        'description' => $service->description,
                        'price' => $service->price,
                        'sort_desc' => $service->sort_desc,
                        'created_at' => $service->created_at,
                        'updated_at' => $service->updated_at,
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Add On Services fetched successfully',
                'data' => $addOnServices
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }

    // public function getUserProfile()
    // {
    //     try {
    //         $user = request()->user_id;
    //         $userData = User::where('id', $user)
    //             ->where('status', 1)
    //             ->where('is_deleted', 0)
    //             ->first(['id', 'name', 'email', 'phone_number', 'alternate_phone', 'avatar as image']);

    //         if ($userData) {
    //             $userData->image = $userData->image ? config('APP_URL') . 'profile_images/' . $userData->image : '';
    //             return response()->json([
    //                 'status' => true,
    //                 'message' => 'User profile fetched successfully',
    //                 'data' => $userData
    //             ], 200);
    //         } else {
    //             return response()->json([
    //                 'status' => false,
    //                 'message' => 'User not found',
    //                 'data' => (object) []
    //             ], 404);
    //         }

    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Something went wrong',
    //             'error' => $th->getMessage()
    //         ], 200);
    //     }
    // }

    public function getUserProfile(Request $request)
    {
        try {
            $userId = $request->user_id;

            $user = User::where('id', $userId)
                ->where('status', 1)
                ->where('is_deleted', 0)
                ->first([
                    'id as user_id',
                    'name',
                    'email',
                    'avatar',
                    // 'gender',
                    'phone_number as mobile',
                    'city',
                    'state',
                    'pincode as zipcode',
                    'landmark',
                    'city'
                ]);

            if ($user) {
                $base_url = $this->base_url . '/';
                $userData = [
                    'user_id' => (string) $user->user_id,
                    'name' => $user->name ?? '',
                    'email' => $user->email ?? '',
                    'profile_pic' => $user->avatar ? $base_url . 'profile_images/' . $user->avatar : '',
                    // 'gender' => $user->gender ?? '',
                    'mobile' => $user->mobile ?? '',
                    'city' => $user->city ?? '',
                    'state' => $user->state ?? '',
                    'zipcode' => $user->zipcode ?? '',
                    'address' => $user->landmark . ', ' . $user->city . ', ' . $user->state . ', ' . $user->zipcode ?? ''
                ];

                // Get latest membership plan (where addon_id is null)
                $memberPlanOrder = \DB::table('plan_orders')
                    ->where('user_id', $userId)
                    ->whereNull('addon_id')
                    ->where('status', 1)
                    ->where('is_deleted', 0)
                    ->latest('created_at')
                    ->first();

                $memberPlan = (object) [];
                if ($memberPlanOrder) {
                    $plan = \DB::table('plans')->where('id', $memberPlanOrder->plan_id)->first();
                    if ($plan) {
                        $memberPlan = [
                            'plan_name' => $plan->plan_name,
                            'description' => $plan->description,
                            'price' => $plan->price,
                            'image' => $plan->image ? $base_url . 'plans/' . $plan->image : '',
                            'validity' => $plan->validity,
                            'purchase_status' => $memberPlanOrder->purchase_status,
                            'total_amount' => $memberPlanOrder->total_price,
                        ];
                    }
                }

                // Get all addon service plans
                $addonOrders = \DB::table('plan_orders')
                    ->where('user_id', $userId)
                    ->whereNotNull('addon_id')
                    ->where('status', 1)
                    ->where('is_deleted', 0)
                    ->get();

                $addonservicePlan = [];
                foreach ($addonOrders as $addon) {
                    $addonPlan = \DB::table('plans')->where('id', $addon->addon_id)->first();
                    if ($addonPlan) {
                        $addonservicePlan[] = [
                            'plan_name' => $addonPlan->plan_name,
                            'description' => $addonPlan->description,
                            'price' => $addonPlan->price,
                            'image' => $addonPlan->image ? $base_url . 'plans/' . $addonPlan->image : '',
                            'validity' => $addonPlan->validity,
                            'purchase_status' => $addon->purchase_status,
                            'total_amount' => $addon->total_price,
                        ];
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Record found Successfully',
                    'data' => [
                        'user' => $userData,
                        'memberPlan' => $memberPlan,
                        'addonservicePlan' => $addonservicePlan
                    ]
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found',
                    'data' => (object) []
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 200);
        }
    }

    public function getNotificationData()
    {
        $pushNotifications = DB::table('newsletter_subscriptions')->where('type', 'push')
            ->select('id', 'user_id', 'email', 'is_active', 'type')
            ->get();

        $emailNotifications = DB::table('newsletter_subscriptions')->where('type', 'email')
            ->select('id', 'user_id', 'email', 'is_active', 'type')
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Notification data fetched successfully.',
            'data' => [
                'email' => $emailNotifications,
                'push' => $pushNotifications
            ]
        ], 200);
    }

    public function updateNotificationData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:newsletter_subscriptions,id',
                'user_id' => 'sometimes|exists:users,id',
                'email' => 'sometimes|email',
                'type' => 'sometimes',
                'is_active' => 'sometimes|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $updateData = [];

            if ($request->has('user_id')) {
                $updateData['user_id'] = $request->user_id;
            }

            if ($request->has('is_active')) {
                $updateData['is_active'] = $request->is_active;
            }

            if (!empty($updateData)) {
                DB::table('newsletter_subscriptions')
                    ->where('id', $request->id)
                    ->update($updateData);
            }
            if (!empty($request->email)) {
                DB::table('newsletter_subscriptions')
                    ->where('id', $request->id)
                    ->update(['email' => $request->email]);
            }

            if (!empty($request->type)) {
                DB::table('newsletter_subscriptions')
                    ->where('id', $request->id)
                    ->update(['type' => $request->type]);
            }

            $updatedData = DB::table('newsletter_subscriptions')
                ->where('id', $request->id)
                ->first();

            $notificationData = [
                'id' => $updatedData->id,
                'user_id' => $updatedData->user_id,
                'email' => $updatedData->email,
                'is_active' => $updatedData->is_active ?? 0,
                'type' => $request->type ?? 'email',
            ];

            return response()->json([
                'status' => true,
                'message' => 'Record updated Successfully',
                'data' => $notificationData,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function newsletterSubscription(Request $request)
    {
        $email = $request->email;

        try {
            if (!$email) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email is required',
                    'data' => (object) []
                ], 400);
            }

            // Check for duplicate subscription
            $existing = DB::table('newsletter_subscriptions')
                ->where('email', $email)
                ->first();

            if ($existing) {
                return response()->json([
                    'status' => false,
                    'message' => 'This email is already subscribed',
                    'data' => (object) $existing
                ], 200); // 409 Conflict
            }

            // Insert new subscription
            DB::table('newsletter_subscriptions')->insert([
                'email' => $email,
                'type' => 'email',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Get the inserted data
            $notification = DB::table('newsletter_subscriptions')
                ->where('email', $email)
                ->first();

            return response()->json([
                'status' => true,
                'message' => 'Subscription successful',
                'data' => (object) $notification
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    public function updateUserProfile(Request $request)
    {
        try {
            $userId = $request->user_id;

            if (!$userId) {
                return response()->json([
                    'status' => false,
                    'message' => 'User ID is required',
                    'data' => (object) []
                ], 400);
            }

            // Prepare data to update
            $updateData = [];

            if ($request->has('name')) {
                $updateData['name'] = $request->name;
            }

            if ($request->has('email')) {
                $updateData['email'] = $request->email;
            }

            if ($request->has('mobile')) {
                // phone_number column in users table
                $updateData['phone_number'] = $request->mobile;
            }

            if ($request->has('phone')) {
                $updateData['alternate_phone'] = $request->phone;
            }

            if ($request->has('city')) {
                $updateData['city'] = $request->city;
            }

            if ($request->has('state')) {
                $updateData['state'] = $request->state;
            }

            if ($request->has('zipcode')) {
                // pincode column in users table
                $updateData['pincode'] = $request->zipcode;
            }

            if ($request->has('address')) {
                // storing in landmark column
                $updateData['landmark'] = $request->address;
            }

            // Handle profile_pic upload if present
            if ($request->hasFile('profile_pic')) {
                $file = $request->file('profile_pic');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('profile_images'), $filename);
                $updateData['avatar'] = $filename;
            }

            if (empty($updateData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'No fields to update',
                    'data' => (object) []
                ], 400);
            }

            // Update the user record
            DB::table('users')->where('id', $userId)->update($updateData);

            // Fetch updated user data to return
            $user = DB::table('users')
                ->where('id', $userId)
                ->where('status', 1)
                ->where('is_deleted', 0)
                ->first([
                    'name',
                    'email',
                    'avatar',
                    'phone_number as mobile',
                    'alternate_phone as phone',
                    'city',
                    'state',
                    'pincode as zipcode',
                    'landmark as address'
                ]);

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'User not found after update',
                    'data' => (object) []
                ], 404);
            }

            $base_url = $this->base_url . '/profile_images/';

            $responseData = [
                'name' => $user->name ?? '',
                'email' => $user->email ?? '',
                'phone' => $user->phone ?? '',
                'profile_pic' => $user->avatar ? $base_url . $user->avatar : '',
                'gender' => '', // as per your example, gender is blank
                'mobile' => $user->mobile ?? '',
                'city' => $user->city ?? '',
                'state' => $user->state ?? '',
                'zipcode' => $user->zipcode ?? '',
                'address' => $user->address ?? '',
            ];

            return response()->json([
                'status' => true,
                'message' => 'Profile updated Successfully',
                'data' => $responseData
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function getHeadwayIT()
    {
        try {
            $base_url = $this->base_url . '/';
            if (substr($base_url, -1) !== '/') {
                $base_url .= '/';
            }

            $ourProducts = OurProduct::where('status', '1')->get(['id', 'photo', 'product_banner', 'title', 'desc', 'play_store', 'app_store', 'web_url', 'tagline'])
                ->map(function ($item) use ($base_url) {
                    return [
                        'id' => $item->id,
                        'photo' => $item->photo ? $base_url . 'products/' . $item->photo : '',
                        'product_banner' => $item->product_banner ? $base_url . 'products/' . $item->product_banner : '',
                        'title' => $item->title,
                        'desc' => $item->desc,
                        'tagline' => $item->tagline ?? '',
                        'play_store' => $item->play_store,
                        'app_store' => $item->app_store,
                        'web_url' => $item->web_url
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Headway IT data fetched successfully',
                'data' => [
                    'our_products' => $ourProducts
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getJewelleryVidyapith()
    {
        // this function to get the ssu gallery data and youtube_link from gen_settings table
        try {
            $base_url = $this->base_url . '/';
            if (substr($base_url, -1) !== '/') {
                $base_url .= '/';
            }
            $galleries = DB::table('ssu_galleries')
                ->get(['id', 'title', 'images'])
                ->map(function ($item) use ($base_url) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'image' => $base_url . 'ssu_gallery/' . $item->images
                    ];
                });

            $youtubeLink = DB::table('settings')
                ->where('name', 'youtube_intro')
                ->value('value');

            return response()->json([
                'status' => true,
                'message' => 'Jewellery Vidyapith data fetched successfully',
                'data' => [
                    'galleries' => $galleries,
                    'youtube_link' => $youtubeLink
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getIntelligentHR()
    {
        try {
            $base_url = $this->base_url . '/';
            if (substr($base_url, -1) !== '/') {
                $base_url .= '/';
            }

            $services = Services::where('status', 1)
                ->where('is_deleted', 0)
                ->where('parent_id', config('custome.HR_ID'))
                ->get(['id', 'name', 'service_desc', 'image'])
                ->map(function ($item) use ($base_url) {
                    return [
                        'id' => $item->id,
                        'title' => $item->name,
                        'description' => $item->service_desc,
                        'image' => $item->image ? $base_url . 'services/' . $item->image : '',
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Intelligent HR data fetched successfully',
                'data' => [
                    'services' => $services
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function getIdbPage()
    {
        try {
            $base_url = $this->base_url . '/';
            if (substr($base_url, -1) !== '/') {
                $base_url .= '/';
            }

            $idplist = Services::where('status', 1)->where('is_deleted', 0)->where('parent_id', config('custome.DEVELOPMENT_BUSSINESS_ID'))->get();
            $idplist = $idplist->map(function ($item) use ($base_url) {
                return [
                    'id' => $item->id,
                    'title' => $item->name,
                    'image' => $item->image ? $base_url . '/services/' . $item->image : '',
                    'description' => $item->service_desc,
                ];
            });

            $reviews = Testimonial::where('status', 1)
                ->where('type', 'idp')
                ->where('is_deleted', 0)
                ->get(['id', 'title as name', 'image', 'description as comment', 'city as location', 'rating', 'shop_name'])
                ->map(function ($item) use ($base_url) {
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'shop_name' => $item->shop_name, // Assuming shop_name is
                        'image' => $item->image ? $base_url . '/testimonials/' . $item->image : '',
                        'location' => $item->location,
                        'comment' => $item->comment,
                        'rating' => $item->rating ? (float) $item->rating : 0, // Ensure rating is an integer
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'IDP Page data fetched successfully',
                'data' => [
                    'idplist' => $idplist,
                    'reviews' => $reviews
                ]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * One Time Request form
     */
    public function oneTimeRequestMeeting(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'shop_name' => 'required|string|max:255',
                'subject' => 'required',
                'message' => 'nullable',
                'phone_number' => 'required|max:10',
                'user_id' => 'required|string|max:255',

            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => (object) [],
                ], 200);
            }
            $data = $request->all();
            $result = OneTimeMeeting::create($data);

            return response()->json(['status' => true, 'message' => 'One Time Request sent successfully.', 'data' => $result], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get get One Time Meeting history list.
     */
    public function getOneTimeList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $serviceData = OneTimeMeeting::where(['status' => 1, 'user_id' => $user_id])->get();
            $serviceCount = $serviceData->count();

            $finalVisits = [];
            // $servicedata = Modules::where(['status' => 1, 'service_id' => 1])->first();
            for ($i = 0; $i < $serviceCount; $i++) {
                $cmdvisitData = OneTimeMeeting::where([
                    'user_id' => $user_id
                ])->orderBy('schedule_date', 'asc')->get();
                $visit = $cmdvisitData[$i] ?? null;

                $finalVisits[] = [
                    'name' => ($serviceData[$i]) ? $serviceData[$i]->name : '',
                    'call_status' => $visit ? ($visit->call_status ?? '') : 'Pending',
                    'schedule_date' => $visit->schedule_date ? (date('d M, Y', strtotime($visit->schedule_date)) ?? '') : '-',
                    'schedule_time' => $visit->schedule_time ? (date(
                        'h:i A',
                        strtotime($visit->schedule_time)
                    ) ?? '') : '-',
                    'remarks' => $visit ? ($visit->remarks ?? '') : '-',
                    'message' => $visit ? ($visit->message ?? '') : '-',
                    'subject' => $visit ? ($visit->subject ?? '') : '-',
                ];
            }

            return response()->json(['status' => true, 'message' => 'Get store visit data successfully.', 'data' => $finalVisits], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * One Time Request form
     */
    public function revisionBatchRequestMeeting(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $form_type = $request->form_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $validator = Validator::make($request->all(), [
                'owner_name' => 'required|string',
                'shop_name' => 'required|string|max:255',
                'subject' => 'required',
                'message' => 'nullable',
                'phone_number' => 'required|max:10',
                'user_id' => 'required|string',
                'form_type' => 'required',
                // Conditional rules
                'image' => 'required_if:cash_payment_request,0|nullable',
                'cash_payment_request' => 'required_if:image,null|in:0,1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => (object) [],
                ], 200);
            }
            $data = $request->all();
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('revision_batch_receipts'), $filename);
                $data['image'] = $filename;
            }
            $result = RevisionBatch::create($data);

            return response()->json(['status' => true, 'message' => 'Request sent successfully.', 'data' => $result], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get get One Time Meeting history list.
     */
    public function getRevisionBatchList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $form_type = $request->form_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $serviceData = RevisionBatch::where(['status' => 1, 'user_id' => $user_id, 'form_type' => $form_type])->get();
            $serviceData = $serviceData->map(function ($serviceData) use ($base_url) {
                return collect($serviceData)->except(['created_at', 'updated_at', 'status'])
                    ->put('image', ($serviceData['image']) ? $base_url . $this->revision_batch_path . $serviceData['image'] : '')
                    ->toArray();
            })->toArray();

            return response()->json(['status' => true, 'message' => 'Get store visit data successfully.', 'data' => $serviceData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get getMyPlanActive list.
     */
    public function getMyPlanActiveList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $loginType = $request->user_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $activePlan = PlanPurchase::with('Plans:id,plan_name,plan_type,page_type,image')->where('user_id', $user_id)->where('purchase_status', 'Approved')->get();

            $activeRevisionPlan = RevisionBatch::select('id as revision_id', 'plan_id')->with('Plans:id,plan_name,plan_type,page_type,image')->where('user_id', $user_id)->where('revison_batch_status', 'Approved')->where('form_type', 'Revision_Batch')->get();

            $activeStayAwarePlan = RevisionBatch::select('id as revision_id', 'plan_id')->with('Plans:id,plan_name,plan_type,page_type,image')->where('user_id', $user_id)->where('revison_batch_status', 'Approved')->where('form_type', 'Stay_Aware')->get();

            $activeOneTimeMeet = OneTimeMeeting::where('user_id', $user_id)->where('call_status', 'Pending')->first();

            $plans = $activePlan->pluck('Plans')->flatten()->map(function ($plan) use ($base_url) {
                return [
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->plan_name,
                    'plan_type' => $plan->plan_type,
                    'page_type' => $plan->page_type,
                    'image' => $plan->image ? $base_url . $this->plan_path . $plan->image : '',
                ];
            });

            $plans_revisions = $activeRevisionPlan->map(function ($revision) use ($base_url) {
                $plan = $revision['Plans'];
                // dd($revision);
                return [
                    'revision_id' => $revision['revision_id'],
                    'plan_id' => $revision['plan_id'] ?? null,
                    'plan_name' => $plan->plan_name ?? '',
                    'plan_type' => 'revision',
                    'page_type' => $plan->page_type ?? '',
                    'image' => $plan && $plan->image ? $base_url . $this->plan_path . $plan->image : '',
                ];
            });

            $plans_stay_aware = $activeStayAwarePlan->map(function ($revision) use ($base_url) {
                $plan = $revision['Plans'];
                // dd($revision);
                return [
                    'revision_id' => $revision['revision_id'],
                    'plan_id' => $revision['plan_id'] ?? null,
                    'plan_name' => $plan->plan_name ?? '',
                    'plan_type' => 'stay_aware',
                    'page_type' => $plan->page_type ?? '',
                    'image' => $plan && $plan->image ? $base_url . $this->plan_path . $plan->image : '',
                ];
            });

            if ($activeOneTimeMeet) {
                $formattedMeet = collect([[
                    'type' => 'one_time_meeting',
                    'plan_id' => 0,
                    'plan_name' => 'One Time Meeting',
                    'plan_type' => '',
                    'page_type' => 'OneTimeMeeting',
                    'image' => '',
                ]]);
            } else {
                $formattedMeet = collect(); // empty collection if no record found
            }

            $merged = $plans->merge($plans_revisions)->merge($formattedMeet)->merge($plans_stay_aware);
            return response()->json(['status' => true, 'message' => 'Get store visit data successfully.', 'data' => $merged], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get subject wise staff training.
     */
    public function getStaffTraining(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $page_type = $request->page_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $subjectData = DB::table('trainer_subjects')
                ->select(
                    'trainer_subjects.id',
                    'trainer_subjects.subject_name'
                )
                ->where('trainer_subjects.status', '1')
                ->where('trainer_subjects.is_deleted', '0')
                ->get();
            // $subjectData = DB::table('trainer_subjects')
            //     ->leftJoin('members_module_subject', function ($join) use ($user_id, $plan_id) {
            //         $join->on('trainer_subjects.id', '=', 'members_module_subject.subject_id')
            //             ->where('members_module_subject.membership_id', '=', $plan_id)
            //             ->where('members_module_subject.member_id', '=', $user_id);
            //     })
            //     ->leftJoin('trainers', 'members_module_subject.trainer_id', '=', 'trainers.id')
            //     ->select(
            //         'trainer_subjects.id',
            //         'trainer_subjects.subject_name',
            //         'trainers.name AS trainers_name',
            //         'members_module_subject.subject_sub_name AS subject_sub_name',
            //         DB::raw("CASE WHEN members_module_subject.date IS NULL THEN '-' ELSE members_module_subject.date END AS date"),
            //         DB::raw("CASE WHEN members_module_subject.time IS NULL THEN '-' ELSE members_module_subject.time END AS time"),
            //         'members_module_subject.remarks',
            //         DB::raw("CASE WHEN members_module_subject.module_status IS NULL THEN 'Pending' ELSE members_module_subject.module_status END AS module_status")
            //     )
            //     ->where('trainer_subjects.status', '1')
            //     ->where('trainer_subjects.is_deleted', '0')
            //     ->distinct()
            //     ->get();
            $subType = [
                'Theory',
                'Practicle',
                'Roleplay',
                "FAQ's Training",
                'Review Session'
            ];

            $mainData = [];
            $finalData = [];
            $subData = [];
            foreach ($subjectData as $subject) {
                $subjectName = $subject->subject_name;
                $subjectId = $subject->id;
                $mainData['id'] = $subject->id;
                $mainData['subject_title'] = $subject->subject_name ?? '-';
                $mainData['trainer_name'] = $subject->trainers_name ?? '-';
                $mainData['status_details'] = [];
                foreach ($subType as $key => $title) {
                    $subQue = DB::table('members_module_subject')
                        ->leftJoin('trainers', 'members_module_subject.trainer_id', '=', 'trainers.id')
                        ->select(
                            'members_module_subject.subject_sub_name',
                            'members_module_subject.date',
                            'members_module_subject.time',
                            'members_module_subject.remarks',
                            'members_module_subject.module_status',
                            'trainers.name AS trainers_name'
                        )
                        ->where('members_module_subject.subject_id', $subjectId)
                        ->where('members_module_subject.subject_sub_name', $title)
                        ->where('members_module_subject.membership_id', $plan_id)
                        ->where('members_module_subject.member_id', $user_id)
                        ->first();
                    // $subjectRow = $subjectData->first(function ($item) use ($title, $subjectId) {
                    //     return $item->subject_sub_name === $title && $item->id === $subjectId;
                    // });
                    $subData['title'] = $title;
                    $subData['trainer_name'] = $subQue->trainers_name ?? '-';
                    $subData['status'] = $subQue->module_status ?? 'Pending';
                    $subData['date_time'] = ($subQue->date ?? '-') . ' ' . ($subQue->time ?? '-');
                    $subData['remarks'] = $subQue->remarks ?? '-';
                    $mainData['status_details'][] = $subData;
                }
                $finalData[] = $mainData;
            }


            return response()->json(['status' => true, 'message' => 'Get subject data successfully.', 'data' => $finalData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get get One Time Meeting history list.
     */
    public function getEventList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $plan_id = $request->plan_id;
            $form_type = $request->form_type;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $serviceData = Event::leftJoin('event_request', function ($join) use ($user_id, $plan_id) {
                $join->on('events.id', '=', 'event_request.event_id')
                    ->where('event_request.user_id', '=', $user_id);
            })
                ->select('events.id', 'events.event_name', 'events.description', 'events.location AS event_building', 'events.event_address', 'events.event_price', 'events.event_date_time',  DB::raw("CASE
                WHEN events.status = 1
                THEN 'Active'
                ELSE 'Completed'
             END AS status"),  DB::raw("CASE
                WHEN event_request.request_status IS NULL OR event_request.request_status = ''
                THEN 'Pending'
                ELSE event_request.request_status
             END AS request_status"))
                ->get();
            $serviceData = $serviceData->map(function ($serviceData) use ($base_url) {
                return collect($serviceData)->except(['created_at', 'updated_at', 'image', 'is_deleted'])
                    ->put('event_date_time', date('Y-m-d A', strtotime($serviceData['event_date_time'])))
                    ->toArray();
            })->toArray();

            return response()->json(['status' => true, 'message' => 'Get Event data successfully.', 'data' => $serviceData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * event Request form
     */
    public function eventRequest(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $token = $request->header('Authorization');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $validator = Validator::make($request->all(), [
                'event_id' => 'required',
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'data' => (object) [],
                ], 200);
            }
            $data = $request->all();

            $result = EventRequest::create($data);

            return response()->json(['status' => true, 'message' => 'Request sent successfully.', 'data' => $result], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }
}
