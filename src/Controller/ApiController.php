<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Config\Definition\Exception\Exception;
use \Doctrine\ORM\Tools\Pagination\Paginator;
use App\Entity\User;
use App\Entity\Track;
use App\Entity\TrackLike;
use App\Entity\Playlist;
use App\Entity\PlaylistLike;


class ApiController extends AbstractController
{

	public function checkAuth(Request $request)
	{
		$user = $this->getDoctrine()->getRepository(User::class)->findByToken($request->headers->get("Authorization"));
		if ($user) {
			$response = $user->getId();

		} else {
			$response = null;         
		}
		return $response;
	}


    /**
     * @Route("/tracks/{id}", name="api_get_tracks", methods={"GET","HEAD"}, defaults={"id"=0})
     */
    public function getTrack(int $id, Request $request)
    {
    	$userId = $this->checkAuth($request);
    	if ($userId) {
    		if (!$id) {
    			$page = intval($request->query->get('page')) ? intval($request->query->get('page')) : 1;
    			$pageSize = intval($request->query->get('size')) ? intval($request->query->get('size')) : 100;
    			$entityManager = $this->getDoctrine()->getManager();
    			$track = $entityManager->getRepository(Track::class);
    			$query = $track->createQueryBuilder('t')->getQuery();
    			// $pageSize = 40;
    			$paginator = new Paginator($query);
    			$totalItems = count($paginator);
    			if ($totalItems) {
    				$pageCount = ceil($totalItems / $pageSize);
    				$paginator
    				->getQuery()
    				->setFirstResult($pageSize * ($page - 1))
    				->setMaxResults($pageSize);
    				$res['Page'] = $page;
    				$res['Page size'] = $pageSize > count($paginator) ? count($paginator) : $pageSize;
    				$res['Page count'] = $pageCount;
    				foreach ($paginator as $pageItem) {
    					$res[$pageItem->getId()] = 
    					[
    						'id' => $pageItem->getId(),
    						'name' => $pageItem->getName(),
    						'playlist' => $pageItem->getPlaylist() ? $pageItem->getPlaylist()->getId() : null,
    						'like' => $this->getDoctrine()->getRepository(TrackLike::class)->findByUser($pageItem->getId(), $userId) ? true : false
    					];
    				}
    			} else {
    				$res = ['message' => "No data"];
    			}
    		} else {
    			$track = $this->getDoctrine()->getRepository(Track::class)->find($id);
    			if ($track) {
    				$res['id'] = $track->getId();
    				$res['name'] = $track->getName();
    				$pl = $track->getPlaylist() ? $track->getPlaylist()->getId() : null;
    				if ($pl) {
    					$res['playlist'] = $pl;
    				}
    			} else {
    				$res = ['message' => "No data"];
    			}
    		}
    		$response = new Response(json_encode($res));
    		$response->headers->set('Content-Type', 'application/json');
    	}  else {
    		$response = new Response('{"message": "Authentication error" }', 401);
    		$response->headers->set('Content-Type', 'application/json');           
    	}
    	return $response;
    }


    /**
      * @Route("/playlists/{id}", name="api_get_playlist", methods={"GET","HEAD"}, defaults={"id"=0})
     */
    public function getPlaylist(int $id, Request $request)
    {
    	$userId = $this->checkAuth($request);
    	if ($userId) {
    		if (!$id) {
    			$page = intval($request->query->get('page')) ? intval($request->query->get('page')) : 1;
    			$pageSize = intval($request->query->get('size')) ? intval($request->query->get('size')) : 100;
    			$entityManager = $this->getDoctrine()->getManager();
    			$playlist = $entityManager->getRepository(Playlist::class);
    			$query = $playlist->createQueryBuilder('t')->getQuery();
    			// $pageSize = 40;
    			$paginator = new Paginator($query);
    			$totalItems = count($paginator);
    			if ($totalItems) {
    				$pageCount = ceil($totalItems / $pageSize);
    				$paginator
    				->getQuery()
    				->setFirstResult($pageSize * ($page - 1))
    				->setMaxResults($pageSize);
    				$res['Page'] = $page;
    				$res['Page size'] = $pageSize > count($paginator) ? count($paginator) : $pageSize;
    				$res['Paginator count'] = $pageCount;
    				foreach ($paginator as $pageItem) {
    					$res[$pageItem->getId()] = 
    					[
    						'id' => $pageItem->getId(),
    						'name' => $pageItem->getName(),
    						'like' => $this->getDoctrine()->getRepository(PlaylistLike::class)->findByUser($pageItem->getId(), $userId) ? true : false
    					];
    					}
    				} else {
    					$res = ['message' => "No data"];
    				}
    			} else {
    				$playlist = $this->getDoctrine()->getRepository(Playlist::class)->find($id);
    				if ($playlist) {
    					$res['id'] = $playlist->getId();
    					$res['name'] = $playlist->getName();
    				} else {
    					$res = ['message' => "No data"];
    				}
    			}
    			$response = new Response(json_encode($res));
    			$response->headers->set('Content-Type', 'application/json');
    		}  else {
    			$response = new Response('{"message": "Authentication error" }', 401);
    			$response->headers->set('Content-Type', 'application/json');           
    		}
    		return $response;
    	}

    /**
     * @Route("/playlists/{id}/tracks", name="api_get_playlists_tracks", methods={"GET","HEAD"})
     */
    public function getPlaylistsTracks(int $id, Request $request)
    {
    	$userId = $this->checkAuth($request);
    	if ($userId) {
    		$playlist = $this->getDoctrine()->getRepository(Playlist::class)->find($id);
    		if ($playlist) {
    			$res = [];
    			$tracks = $playlist->getTracks();
    			foreach ($tracks as $key => $track) {
    				$res[$key] = 
    				[
    					'id' => $track->getId(),
    					'name' => $track->getName(),
    				];
    			}
    		} else {
    			$res = ['message' => "No data"];
    		}
    		$response = new Response(json_encode($res));
    		$response->headers->set('Content-Type', 'application/json');
    	}  else {
    		$response = new Response('{"message": "Authentication error" }', 401);
    		$response->headers->set('Content-Type', 'application/json');           
    	}
    	return $response;
    }


    /**
     * @Route("/tracks/{id}/playlist", name="api_get_tracks_playlists", methods={"GET","HEAD"})
     */
    public function getTracksPlaylist(int $id, Request $request)
    {
    	$userId = $this->checkAuth($request);
    	if ($userId) {
    		$tracks = $this->getDoctrine()->getRepository(Track::class)->find($id);
    		if ($tracks) {
    			$res = [];
    			$playlist = $tracks->getPlaylist();
    			$res = 
    			[
    				'id' => $playlist->getId(),
    				'name' => $playlist->getName(),
    			];
    		} else {
    			$res = ['message' => "No data"];
    		}
    		$response = new Response(json_encode($res));
    		$response->headers->set('Content-Type', 'application/json');
    	}  else {
    		$response = new Response('{"message": "Authentication error" }', 401);
    		$response->headers->set('Content-Type', 'application/json');           
    	}
    	return $response;
    }


    /**
     * @Route("/tracks/{id}/like", name="api_track_like", methods={"GET","HEAD","PUT"})
     */
    public function setTrackLike(int $id, Request $request)
    {
    	$userId = $this->checkAuth($request);
    	if ($userId) {
    		$track = $this->getDoctrine()->getRepository(Track::class)->find($id);
    		if ($track) {
    			$trackLike = $this->getDoctrine()->getRepository(TrackLike::class)->findOneBy(['_user' => $userId, 'track' => $track->getId()]);
    			if (!$trackLike){
    				$entityManager = $this->getDoctrine()->getManager();
    				$trackLike = new TrackLike();
    				$user = $this->getDoctrine()->getRepository(User::class)->find($userId);
    				$trackLike->setUser($user);
    				$trackLike->setTrack($track);

    				$playlist = $track->getPlaylist();
    				$playlistLike = $this->getDoctrine()->getRepository(PlaylistLike::class)->findOneBy(['_user' => $userId, 'playlist' => $playlist->getId()]);
    				if (!$playlistLike){
    					$playlistLike = new PlaylistLike();
    					$playlistLike->setUser($user);
    					$playlistLike->setPlaylist($playlist);
    					$playlistLike->setByTrack($track);
    					$entityManager->persist($playlistLike);
    					$entityManager->flush();
    				}

    				$entityManager->persist($trackLike);
    				$entityManager->flush();
    			}
    			$res = ['message' => "like"];

    		} else {
    			$res = ['message' => "Track not found"];
    		}
    		$response = new Response(json_encode($res));
    		$response->headers->set('Content-Type', 'application/json');
    	}  else {
    		$response = new Response('{"message": "Authentication error" }', 401);
    		$response->headers->set('Content-Type', 'application/json');           
    	}
    	return $response;
    }


    /**
     * @Route("/tracks/{id}/unlike", name="api_track_unlike", methods={"GET","HEAD","PUT"})
     */
    public function setTrackUnlike(int $id, Request $request)
    {
    	$userId = $this->checkAuth($request);
    	if ($userId) {
    		$track = $this->getDoctrine()->getRepository(Track::class)->find($id);
    		if ($track) {
    			$trackLike = $this->getDoctrine()->getRepository(TrackLike::class)->findOneBy(['_user' => $userId, 'track' => $track->getId()]);
    			if ($trackLike){
    				$entityManager = $this->getDoctrine()->getManager();
    				$entityManager->remove($trackLike);
    				$entityManager->flush();

    				$playlist = $track->getPlaylist();
    				$playlistsLike = $this->getDoctrine()->getRepository(PlaylistLike::class)->findBy(['_user' => $userId, 'playlist' => $playlist->getId(), 'byTrack' => $track->getId()]);
    				if ($playlistsLike){
    					foreach ($playlistsLike as $playlistLike) {
    						$entityManager->remove($playlistLike);
    						$entityManager->flush();
    					}
    				}
    				$entityManager->remove($trackLike);
    				$entityManager->flush();
    			}
    			$res = ['message' => "unlike"];

    		} else {
    			$res = ['message' => "Track not found"];
    		}
    		$response = new Response(json_encode($res));
    		$response->headers->set('Content-Type', 'application/json');
    	}  else {
    		$response = new Response('{"message": "Authentication error" }', 401);
    		$response->headers->set('Content-Type', 'application/json');           
    	}
    	return $response;
    }

    /**
     * @Route("/playlists/{id}/like", name="api_playlist_like", methods={"GET","HEAD","PUT"})
     */
    public function setPlaylistLike(int $id, Request $request)
    {
    	$userId = $this->checkAuth($request);
    	if ($userId) {
    		$playlist = $this->getDoctrine()->getRepository(Playlist::class)->find($id);
    		if ($playlist) {
    			$entityManager = $this->getDoctrine()->getManager();
    			$playlistLike = $this->getDoctrine()->getRepository(PlaylistLike::class)->findOneBy(['_user' => $userId, 'playlist' => $playlist->getId()]);
    			if (!$playlistLike){
    				$playlistLike = new PlaylistLike();
    				$user = $this->getDoctrine()->getRepository(User::class)->find($userId);
    				$playlistLike->setUser($user);
    				$playlistLike->setPlaylist($playlist);

    				$entityManager->persist($playlistLike);
    				$entityManager->flush();
    			} else {
    				$playlistLike->setByTrack(null);
    				$entityManager->persist($playlistLike);
    				$entityManager->flush();
    			}
    			$res = ['message' => "like"];

    		} else {
    			$res = ['message' => "Playlist not found"];
    		}
    		$response = new Response(json_encode($res));
    		$response->headers->set('Content-Type', 'application/json');
    	}  else {
    		$response = new Response('{"message": "Authentication error" }', 401);
    		$response->headers->set('Content-Type', 'application/json');           
    	}
    	return $response;
    }


    /**
     * @Route("/playlists/{id}/unlike", name="api_playlist_unlike", methods={"GET","HEAD","PUT"})
     */
    public function setPlaylistUnlike(int $id, Request $request)
    {
    	$userId = $this->checkAuth($request);
    	if ($userId) {
    		$playlist = $this->getDoctrine()->getRepository(Playlist::class)->find($id);
    		if ($playlist) {
    			$playlistLike = $this->getDoctrine()->getRepository(PlaylistLike::class)->findOneBy(['_user' => $userId, 'playlist' => $playlist->getId()]);
    			if ($playlistLike){
    				$entityManager = $this->getDoctrine()->getManager();
    				$entityManager->remove($playlistLike);
    				$entityManager->flush();
    			}
    			$res = ['message' => "unlike"];

    		} else {
    			$res = ['message' => "Playlist not found"];
    		}
    		$response = new Response(json_encode($res));
    		$response->headers->set('Content-Type', 'application/json');
    	}  else {
    		$response = new Response('{"message": "Authentication error" }', 401);
    		$response->headers->set('Content-Type', 'application/json');           
    	}
    	return $response;
    }

   /**
    * @Route("/api/application", name="api_set_application", methods={"POST"})
    */
   public function setApplication(Request $request)
   {
   	if ($request->headers->get("authorization") === $this->token) {
   		$content =  $request->getContent();
   		$content_array = json_decode($request->getContent(), true);

   	}
   	else{
   		$response = new Response('{"message": "Authentication error" }', 401);
   		$response->headers->set('Content-Type', 'application/json');           
   	}
   	return $response; 
   }
}