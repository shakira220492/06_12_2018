<?php

namespace Profile2Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@Profile2/Default/index.html.twig');
    }
    
    public function getDataminingSongsHomeAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {

            $em = $this->getDoctrine()->getManager();

            $user = $em->createQuery(
                "SELECT u.userId 
                FROM HomeBundle:User u"
            ); // validar usuarios que tengan videos, y luego usuarios con videos similares a las busquedas
            $user_e = $user->getResult();
            
            $amountUsers = 0;
            while (isset($user_e[$amountUsers]['userId'])) {
                $amountUsers++; // 
            }
            
            $users = 0;
            while ($users < $amountUsers) {
                
                $respectlyUserId = $user_e[$users]['userId'];
                $video = $em->createQuery(
                    "SELECT v.videoId, v.videoName, v.videoDescription, v.videoImage, 
                    v.videoContent, v.videoUpdatedate, v.videoAmountViews, 
                    v.videoAmountComments, v.videoLikes, v.videoDislikes, u.userId, u.userName 
                    FROM HomeBundle:Video v 
                    JOIN HomeBundle:User u 
                    WITH u.userId = v.user
                    WHERE u.userId = '$respectlyUserId'
                    "
                );
                $video_e = $video->getResult();
                $amountVideos_perUser = 0;
                while (isset($video_e[$amountVideos_perUser]['videoId'])) 
                {
                    $amountVideos_perUser++;
                }
                
                $videos = 0;
                while ($videos <= $amountVideos_perUser) {
                    
                    if (isset($video_e[$videos]['videoId'])) {
                        
                        $videoUpdatedate = $video_e[$videos]['videoUpdatedate'];
                        $videoUpdatedateString = $videoUpdatedate->format('d-M-Y');

                        $videoAmountViews = $video_e[$videos]['videoAmountViews'];
                        $videoAmountViewsFormat = number_format($videoAmountViews);

                        $videoAmountComments = $video_e[$videos]['videoAmountComments'];
                        $videoAmountCommentsFormat = number_format($videoAmountComments);

                        $videoId_Value = $video_e[$videos]['videoId'];
                        $videoName_Value = $video_e[$videos]['videoName'];
                        $videoDescription_Value = $video_e[$videos]['videoDescription'];
                        $videoImage_Value = $video_e[$videos]['videoImage'];
                        $videoContent_Value = $video_e[$videos]['videoContent'];
                        $videoUpdatedate_Value = $videoUpdatedateString;
                        $videoAmountViews_Value = $videoAmountViewsFormat;
                        $videoAmountComments_Value = $videoAmountCommentsFormat;
                        $videoLikes_Value = $video_e[$videos]['videoLikes'];
                        $videoDislikes_Value = $video_e[$videos]['videoDislikes'];
                        $userId_Value = $video_e[$videos]['userId'];
                        $userName_Value = $video_e[$videos]['userName'];
                        $amountVideos_Value = $amountVideos_perUser;
                        
                        $videosInformation[$users][$videos] = array(
                            'videoId' => $videoId_Value,
                            'videoName' => $videoName_Value,
                            'videoDescription' => $videoDescription_Value,
                            'videoImage' => $videoImage_Value,
                            'videoContent' => $videoContent_Value,
                            'videoUpdatedate' => $videoUpdatedate_Value,
                            'videoAmountViews' => $videoAmountViews_Value,
                            'videoAmountComments' => $videoAmountComments_Value,
                            'videoLikes' => $videoLikes_Value,
                            'videoDislikes' => $videoDislikes_Value,
                            'userId' => $userId_Value,
                            'userName' => $userName_Value,
                            'amountVideos' => $amountVideos_Value,
                            'amountUsers' => $amountUsers
                        );

                    } else {
                        $videosInformation[$users][$videos] = array(
                            'videoId' => 0,
                            'videoName' => "",
                            'videoDescription' => "",
                            'videoImage' => "",
                            'videoContent' => "",
                            'videoUpdatedate' => "",
                            'videoAmountViews' => 0,
                            'videoAmountComments' => 0,
                            'videoLikes' => 0,
                            'videoDislikes' => 0,
                            'userId' => 0,
                            'userName' => "",
                            'amountVideos' => 0,
                            'amountUsers' => $amountUsers
                        );
                    }

                    $videos++;
                }
                $users++;
            }
            
            if ($users === 0)
            {
                $videoId_Value = "_";
                $videoName_Value = "_";
                $videoDescription_Value = "_";
                $videoImage_Value = "_";
                $videoContent_Value = "_";
                $videoUpdatedate_Value = "_";
                $videoAmountViews_Value = "_";
                $videoAmountComments_Value = "_";
                $videoLikes_Value = "_";
                $videoDislikes_Value = "_";
                $userId_Value = "_";
                $userName_Value = "_";
                $amountVideos_Value = $amountVideos_perUser;

                $videosInformation[0][0] = array(
                    'videoId' => $videoId_Value,
                    'videoName' => $videoName_Value,
                    'videoDescription' => $videoDescription_Value,
                    'videoImage' => $videoImage_Value,
                    'videoContent' => $videoContent_Value,
                    'videoUpdatedate' => $videoUpdatedate_Value,
                    'videoAmountViews' => $videoAmountViews_Value,
                    'videoAmountComments' => $videoAmountComments_Value,
                    'videoLikes' => $videoLikes_Value,
                    'videoDislikes' => $videoDislikes_Value,
                    'userId' => $userId_Value,
                    'userName' => $userName_Value,
                    'amountVideos' => $amountVideos_Value,
                    'amountUsers' => $amountUsers
                );
            }
            return new Response(json_encode($videosInformation), 200, array('Content-Type' => 'application/json'));
        }
    }
}