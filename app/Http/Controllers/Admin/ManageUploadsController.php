<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;
use RuntimeException;
use WebDevEtc\BlogEtc\Middleware\UserCanManageBlogPosts;
use WebDevEtc\BlogEtc\Models\UploadedPhoto;
use WebDevEtc\BlogEtc\Requests\UploadImageRequest;
use WebDevEtc\BlogEtc\Services\PostsService;
use WebDevEtc\BlogEtc\Services\UploadsService;

/**
 * Class BlogEtcAdminController.
 */
class ManageUploadsController extends Controller
{
    /**
     * @var UploadsService
     */
    private $uploadsService;
    /**
     * @var PostsService
     */
    private $postsService;

    /**
     * BlogEtcAdminController constructor.
     */
    public function __construct(UploadsService $uploadsService, PostsService $postsService)
    {
       // $this->middleware(UserCanManageBlogPosts::class);
        $this->uploadsService = $uploadsService;
        $this->postsService = $postsService;

        if (!config('blogetc.image_upload_enabled')) {
            throw new RuntimeException('The blogetc.php config option is missing or has not enabled image uploading');
        }
    }

    /**
     * Show the main listing of uploaded images.
     *
     * @return mixed
     */
    public function index()
    {
        return view('blogetc_admin::imageupload.index', [
            'uploaded_photos' => UploadedPhoto::orderBy('id', 'desc')
                ->paginate(10),
        ]);
    }

    /**
     * show the form for uploading a new image.
     *
     * @return Factory|View
     */
    public function create()
    {
        return view('blogetc_admin::imageupload.create', []);
    }

    /**
     * Save a new uploaded image.
     *
     * @throws Exception
     */
    public function store(UploadImageRequest $request)
    {
        // Uses some legacy code - this will be refactored and fixed soon!
        $processed_images = $this->uploadsService->legacyProcessUploadedImagesSingle($request);

        return view('blogetc_admin::imageupload.uploaded', ['images' => $processed_images]);
    }

    public function deletePostImage(int $postId)
    {
        return view('blogetc_admin::imageupload.delete-post-image', ['postId' => $postId]);
    }

    public function deletePostImageConfirmed(int $postId)
    {
        $post = $this->postsService->findById($postId);

        $deletedSizes = $this->uploadsService->deletePostImage($post);
        $this->postsService->clearImageSizes($post, $deletedSizes);

        return view('blogetc_admin::imageupload.deleted-post-image');
    }
}
