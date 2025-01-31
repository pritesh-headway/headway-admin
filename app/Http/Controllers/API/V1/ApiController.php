<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\CurlApiService;
use App\Services\FcmNotificationService;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use App\Exports\CouponsExport;
use App\Models\Addon;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Client;
use App\Models\CmdVIsit;
use App\Models\Cms;
use App\Models\Contact;
use App\Models\Membership;
use App\Models\NotificationSetting;
use App\Models\OurCourses;
use App\Models\Plan;
use App\Models\Services;
use App\Models\UserDevices;
use App\Models\Video;
use DOMDocument;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;


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
    protected $fcmNotificationService;
    protected $curlApiService;
    public function __construct(CurlApiService $curlApiService, FcmNotificationService $fcmNotificationService)
    {
        $this->per_page_show = 50;
        $this->base_url = url('/');
        $this->profile_path = '/public/profile_images/';
        $this->banner_path = '/public/banner/';
        $this->client_path = '/public/clients/';
        $this->blog_path = '/public/blogs/';
        $this->plan_path = '/plans/';
        $this->visit_path = '/public/visit/';
        $this->icon_path = '/public/icon/';
        $this->hr_path = '/public/hr/';
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
     * Login User
     */
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'country_code' => 'required|digits:2',
                'mobile' => 'required|min:10|digits:10',
                'otp' => 'required|min:4|digits:4',
                'device_type' => 'required',
            ]);

            if ($validator->fails()) {
                $result['status'] = false;
                $result['message'] = $validator->errors()->first();
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

            $mobile = $request->mobile;
            $otp = $request->otp;
            $device_token = isset($request->device_token) ?? '';
            $device_type = isset($request->device_type) ?? '';
            $api_version = isset($request->api_version) ?? '';
            $app_version = isset($request->app_version) ?? '';
            $os_version = isset($request->os_version) ?? '';
            $device_model_name = isset($request->device_model_name) ?? '';
            $app_language = isset($request->app_language) ?? '';
            $base_url = $this->base_url;
            $user = User::where('phone_number', $mobile)->where('status', operator: 1)->first();
            if (!$user || $user->otp !== $otp || Carbon::now()->greaterThan($user->otp_expires_at)) {
                $result['status'] = false;
                $result['message'] = 'Invalid OTP or OTP expired';
                $result['data'] = (object) [];
                return response()->json($result, 200);
            }

            // Create token or session
            $token = $user->createToken('authToken')->plainTextToken;

            $user->otp = null; // Clear the OTP
            $user->otp_expires_at = null; // Clear OTP expiration
            $user->is_first_time = 0;
            $user->remember_token = $token;
            $user->save();

            $arr = [
                'status' => 1,
                'device_token' => $device_token,
                'device_type' => $device_type,
                'api_version' => $api_version,
                'app_version' => $app_version,
                'os_version' => $os_version,
                'device_model_name' => $device_model_name,
                'login_token' => $token,
                'user_id' => $user->id,
            ];
            DB::table('user_devices')->insertGetId($arr);
            $userData = User::where('phone_number', $mobile)->where('status', operator: 1)->get();
            $data = $userData->map(function ($user) use ($base_url, $token) {
                return collect($user)->except(['password', 'email_verified_at', 'otp', 'otp_expires_at', 'remember_token'])
                    ->put('user_id', $user['id'])
                    ->put('token', $token)
                    ->put('avatar', ($user['avatar']) ? $base_url . $this->profile_path . $user['avatar'] : '')
                    ->toArray();
            })->first();

            return response()->json(['status' => true, 'message' => 'Login successfully!', 'data' => $data]);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }

            $banner = Banner::where('status', 1)->get();
            $banner = $banner->map(function ($user) use ($base_url) {
                return collect($user)->except(['password', 'role_id', 'email_verified_at'])
                    ->put('banner_image', ($user['image']) ? $base_url . $this->banner_path . $user['image'] : '')
                    ->toArray();
            })->toArray();

            $clients = Client::where('status', 1)->get();
            $clients = $clients->map(function ($client) use ($base_url) {
                return collect($client)->except(['password', 'role_id', 'email_verified_at'])
                    ->put('client_image', ($client['image']) ? $base_url . $this->client_path . $client['image'] : '')
                    ->toArray();
            })->toArray();

            $blogs = Blog::where('status', 1)->first();
            if ($blogs) {
                $blogs = collect($blogs)
                    ->except(['password', 'role_id', 'email_verified_at'])
                    ->put('blog_image', $blogs['image'] ? $base_url . $this->blog_path . $blogs['image'] : '')
                    ->toArray();
                $blogs = (object) $blogs;
            }

            $Middlebanner = Banner::where('status', 1)->first();
            if ($Middlebanner) {
                $Middlebanner = collect($Middlebanner)
                    ->except(['password', 'role_id', 'email_verified_at'])
                    ->put('banner_image', $Middlebanner['image'] ? $base_url . $this->banner_path . $Middlebanner['image'] : '')
                    ->toArray();
                $Middlebanner = (object) $Middlebanner;
            }

            $Activeplans = '';
            $we_do_title = '';
            $we_do_info = '';
            $Isverify  = '1';
            $ourServices = [];

            $all_data = array(
                'bannerData' => $banner,
                'clientsData' => $clients,
                'blogsData' => $blogs,
                'active_plan' => $Activeplans,
                'our_services' => $ourServices,
                'middleBannerData' => $Middlebanner,
                'we_do_title' => $we_do_title,
                'we_do_info' => $we_do_info,
                'Isverify' => $Isverify,
            );

            return response()->json(['status' => true, 'message' => 'Get Dashboard data successfully', 'data' => $all_data], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $plansPaginator = Plan::where('status', 1)->paginate($this->per_page_show, ['*'], 'page', $page_number);
            $plans = $plansPaginator->getCollection()->map(function ($plan) use ($base_url) {
                $plan = collect($plan)
                    ->put('plan_image', $plan['image'] ? $base_url . $this->plan_path . $plan['image'] : '')
                    ->toArray();
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

            return response()->json(['status' => true, 'message' => 'Get Plans data successfully', 'data' => $plansData], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $plansPaginator = Plan::where('status', 1)->where('id', $plan_id)->get();
            $plans = $plansPaginator->map(function ($plan) use ($base_url) {
                return collect($plan)->except(['password', 'role_id', 'email_verified_at'])
                    ->put('plan_image', ($plan['image']) ? $base_url . $this->plan_path . $plan['image'] : '')
                    ->toArray();
            })->toArray();

            $result = [];
            $finalData = [];
            $id = 1;

            foreach ($plans as $li) {
                $result = [
                    'id' => $li['id'],
                    'plan_name' => $li['plan_name'],
                    'plan_image' => $li['plan_image'],
                    'sort_desc' => $li['sort_desc'],
                    'price' => $li['price'],
                    'validity' => $li['validity'],
                    'session' => $li['session'],
                    'duration' => $li['duration'],
                    'tax' => $li['tax'],
                    'points' => [],
                ];
                $lis = explode(',', $li['description']); // Split service_desc into an array
                foreach ($lis as $value) {
                    $result['points'][] = [
                        'id' => $id,
                        'name' => trim($value), // Trim whitespace
                    ];
                    $id++;
                }
                $finalData = $result; // Add the processed result to finalData
            }


            return response()->json(['status' => true, 'message' => 'Get Plans data successfully', 'data' => $finalData], 200);
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
            $loginType = $request->user_type;
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $blogsPaginator = Addon::where('status', 1)->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $blogs = $blogsPaginator->getCollection()->map(function ($blog) use ($base_url) {
                $blog = collect($blog)
                    // ->put('blog_image', $blog['image'] ? $base_url . $this->blog_path . $blog['image'] : '')
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

            return response()->json(['status' => true, 'message' => 'Get Add On data successfully', 'data' => $blogsData], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $blogsPaginator = Addon::where('status', 1)->where('id', $addon_id)->first();



            return response()->json(['status' => true, 'message' => 'Get Add On data successfully', 'data' => $blogsPaginator], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $coursesPaginator = OurCourses::where('status', 1)->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $courses = $coursesPaginator->getCollection()->map(function ($course) use ($base_url) {
                $course = collect($course)
                    ->put('course_url', $course['video_url'] ? $base_url  . $course['video_url'] : '')
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

            return response()->json(['status' => true, 'message' => 'Get Add On data successfully', 'data' => $coursesData], 200);
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
            $loginType = $request->user_type;
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $cmdvisitPaginator = CmdVIsit::where('status', 1)->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $cmdvisit = $cmdvisitPaginator->getCollection()->map(function ($visit) use ($base_url) {
                $visit = collect($visit)
                    ->put('visit_image', $visit['image'] ? $base_url . $this->visit_path . $visit['image'] : '')
                    ->toArray();
                return $visit;
            });

            // Replace the original collection with the transformed one
            $cmdvisitPaginator->setCollection(collect($cmdvisit));

            // Get pagination metadata
            $pagination = [
                'total' => $cmdvisitPaginator->total(),
                'count' => $cmdvisitPaginator->count(),
                'per_page' => $cmdvisitPaginator->perPage(),
                'current_page' => $cmdvisitPaginator->currentPage(),
                'total_pages' => $cmdvisitPaginator->lastPage(),
            ];

            $coursesData = [
                'pagination' => $pagination,
                'data' => $cmdvisit,
            ];

            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully', 'data' => $coursesData], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $servicesPaginator = Services::where('status', 1)->paginate($this->per_page_show, ['*'], 'page', $page_number);

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

            return response()->json(['status' => true, 'message' => 'Get service list data successfully', 'data' => $coursesData], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $startupPaginator = Services::select('id', 'name', 'service_desc')->where('status', 1)->where('parent_id', config('custome.STARTUP_ID'))->get()->map(function ($item) {
                $item->service_desc = strip_tags($item->service_desc); // Remove HTML tags
                return $item;
            });

            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully', 'data' => $startupPaginator], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get make business list.
     */
    public function getBusinessList(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $startupPaginator = Services::select('id', 'name', 'service_desc')->where('status', 1)->where('parent_id', config('custome.BUSSINESS_ID'))->get();

            $result = [];
            $finalData = [];
            $id = 1;

            foreach ($startupPaginator as $li) {
                $result = [
                    'name' => $li['name'],
                    'id' => $li['id'],
                    'points' => [],
                ];

                $lis = explode(',', $li['service_desc']); // Split service_desc into an array
                foreach ($lis as $value) {
                    $result['points'][] = [
                        'id' => $id,
                        'name' => trim($value), // Trim whitespace
                    ];
                    $id++;
                }

                $finalData[] = $result; // Add the processed result to finalData
            }

            // dd($finalData);

            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully', 'data' => $finalData], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $title = Services::select('id', 'name')->where('status', 1)->where('id', 22)->where('parent_id', config('custome.DEVELOPMENT_BUSSINESS_ID'))->get();
            $startupPaginator = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('parent_id', config('custome.DEVELOPMENT_BUSSINESS_ID'))->get()->map(function ($service) {
                $service->image = url($this->service_path . $service->image);
                $service->service_desc = strip_tags($service->service_desc);
                return $service;
            });
            $final = ['title' => $title, 'detail' => $startupPaginator];
            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully', 'data' => $final], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $startupPaginator = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('parent_id', config('custome.HR_ID'))->get()->map(function ($service) {
                $service->image = url($this->service_path . $service->image);
                $service->service_desc = strip_tags($service->service_desc);
                return $service;
            });

            return response()->json(['status' => true, 'message' => 'Get Hr data successfully', 'data' => $startupPaginator], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $banner = Services::select('id', 'image')->where('status', 1)->where('id', 20)->where('parent_id', config('custome.VIDYAPITH_ID'))->orderBy('id', 'asc')->first();

            $info = Services::select('id', 'service_desc')->where('status', 1)->where('id', 15)->where('parent_id', config('custome.VIDYAPITH_ID'))->orderBy('id', 'asc')->first();

            if ($banner) {
                $banner->image = url($this->service_path . $banner->image); // Update the path as per your storage structure
            }

            $points = Services::select('sort_desc')->where('status', 1)->where('id', 21)->where('parent_id', config('custome.VIDYAPITH_ID'))->orderBy('id', 'asc')->get();
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

            $startupPaginator = Services::select('id', 'name', 'service_desc')->whereNotIn('id', [15])->where('status', 1)->where('parent_id', config('custome.VIDYAPITH_ID'))->orderBy('id', 'asc')->get();
            $detail = ['info' => strip_tags($info->service_desc), 'banner' => $banner->image, 'points' => $result, 'detail' => $startupPaginator];
            return response()->json(['status' => true, 'message' => 'Get Hr data successfully', 'data' => $detail], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $design = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('name', 'LIKE', '%Design%')->where('parent_id', config('custome.IT_ID'))->get()->map(function ($services) {
                $services->image = url($this->service_path . $services->image);
                $services->service_desc = strip_tags($services->service_desc);
                return $services;
            });

            $development = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('name', 'LIKE', '%Development%')->where('parent_id', config('custome.IT_ID'))->get()->map(function ($service) {
                $service->image = url($this->service_path . $service->image);
                $service->service_desc = strip_tags($service->service_desc);
                return $service;
            });

            $marketing = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('name', 'LIKE', '%marketing%')->where('parent_id', config('custome.IT_ID'))->get()->map(function ($service) {
                $service->image = url($this->service_path . $service->image);
                $service->service_desc = strip_tags($service->service_desc);
                return $service;
            });

            $finalData = ['design' => $design, 'development' => $development, 'marketing' => $marketing];
            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully', 'data' => $finalData], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $startupPaginator = Services::select('id', 'name', 'service_desc', 'image')->where('status', 1)->where('parent_id', config('custome.HEADWAY_INITIATIVE_ID'))->get()->map(function ($service) {
                $service->image = url($this->service_path . $service->image);
                $service->service_desc = strip_tags($service->service_desc);
                return $service;
            });

            return response()->json(['status' => true, 'message' => 'Get cmd visit data successfully', 'data' => $startupPaginator], 200);
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
            $token = $request->header('token');
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
                $result['data'] = (object)[];
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $blogsPaginator = Video::where('status', 1)->where('type', 'Youtube')->paginate($this->per_page_show, ['*'], 'page', $page_number);

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

            return response()->json(['status' => true, 'message' => 'Get Video data successfully', 'data' => $blogsData], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $blogsPaginator = Video::where('status', 1)->where('type', 'Training')->paginate($this->per_page_show, ['*'], 'page', $page_number);

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

            return response()->json(['status' => true, 'message' => 'Get Training Video data successfully', 'data' => $blogsData], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }
            $blogsPaginator = Blog::where('status', 1)->paginate($this->per_page_show, ['*'], 'page', $page_number);

            $blogs = $blogsPaginator->getCollection()->map(function ($blog) use ($base_url) {
                $blog = collect($blog)
                    ->put('blog_image', $blog['image'] ? $base_url . $this->blog_path . $blog['image'] : '')
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

            return response()->json(['status' => true, 'message' => 'Get Blog data successfully', 'data' => $blogsData], 200);
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
            $token = $request->header('token');
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $userData = json_decode($checkToken->getContent(), true);
            if ($userData['status'] == false) {
                return $checkToken->getContent();
            }

            $NotificationSetting = NotificationSetting::where('user_id', $user_id)->first();

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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $validator = Validator::make($request->all(), [
                // Personal Information
                'full_name' => 'required|string|max:255',
                'gender' => 'required|in:Male,Female,Other',
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
                'landline_no' => 'nullable|digits_between:6,15',
                'contact_person_name' => 'nullable|string|max:255',
                'contact_person_mobile' => 'nullable|digits:10',

                // Business Information
                'organization_name' => 'required|string|max:255',
                'registered_office_address' => 'required|string|max:500',
                'gst_no' => 'required|regex:/^(\d{2}[A-Z]{5}\d{4}[A-Z]{1}[A-Z\d]{1}[Z]{1}[A-Z\d]{1})$/',
                'pan_no' => 'required|regex:/^[A-Z]{5}\d{4}[A-Z]{1}$/',
                'date_of_incorporation' => 'required|date',
                'organization_type' => 'required|string|max:255',

                // Bank Details
                'bank_name' => 'required|string|max:255',
                'account_no' => 'required|digits_between:9,18',
                'branch_name' => 'required|string|max:255',
                'ifsc_code' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',

                // Product Details
                'product_type' => 'required|string|max:255',
                'duration' => 'required|string|max:255',
                'deliverables' => 'required|string|max:255',
                'meeting_duration' => 'required|string|max:255',
                'material_provided' => 'required|string|max:1000',

                // Payment Details
                'payment_account_name' => 'required|string|max:255',
                'payment_bank_name' => 'required|string|max:255',
                'payment_account_no' => 'required|digits_between:9,18',
                'payment_ifsc_code' => 'required|regex:/^[A-Z]{4}0[A-Z0-9]{6}$/',
                'payment_receipt' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors()->first(),
                ], 422);
            }
            $data = $request->all();
            $result = Membership::create($data);

            return response()->json(['status' => true, 'message' => 'Data saved successfully.', 'data' => $result], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * get Privacy data.
     */
    public function getPrivacyPolicy(Request $request)
    {
        try {
            $base_url = $this->base_url;
            $user_id = $request->user_id;
            $loginType = $request->user_type;
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $blogsPaginator = Cms::where('status', 1)->where('id', 2)->get();



            return response()->json(['status' => true, 'message' => 'Get Privacy Policy data successfully', 'data' => $blogsPaginator], 200);
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
            $token = $request->header('token');
            $checkToken = $this->tokenVerify($token);
            $page_number = $request->page;

            $blogsPaginator = Cms::where('status', 1)->where('id', 3)->get();



            return response()->json(['status' => true, 'message' => 'Get Privacy Policy data successfully', 'data' => $blogsPaginator], 200);
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => 'Something went wrong', 'error' => $th->getMessage()], 200);
        }
    }

    /**
     * Logout functionality
     */
    public function logout(Request $request)
    {
        $token = $request->header('token');
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
}
