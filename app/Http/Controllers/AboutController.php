<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Model\Article;
use App\User;
use Illuminate\Http\Request;

class AboutController extends Controller
{

    public function downloadColorTalk()
    {
        $file = storage_path() . "/app/colortalk_release.apk";
        $headers = array(
            'Content-Type: application/vnd.android.package-archive',
        );
        return response()->download($file, 'colortalk.apk', $headers);
    }


    public function showResume()
    {
        return redirect('http://yogiai.digitcv.com/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        //

        $userInfo = User::getUserInfoModelByUserId($id);
        if (empty($userInfo)) {
            return redirect('/');
        }
        $userArticle = Article::getArticleModelByUserId($id);
        viewInit();
        return homeView('about', [
            'userInfo' => $userInfo,
            'userArticle' => $userArticle
        ]);
    }

}
