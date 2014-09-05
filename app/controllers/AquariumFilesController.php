<?php

class AquariumFilesController extends BaseController
{

  public function index($aquariumID)
  {
    $files = AquariumFile::where('userID', '=', Auth::id())
      ->where('aquariumID', '=', $aquariumID)
      ->orderBy('createdAt', 'desc')
      ->get();
    return View::make('aquariums/files/index')
      ->with('files', $files)
      ->with('aquariumID', $aquariumID);
  }

  public function getPublicPhotos($aquariumID)
  {
    $aquarium = Aquarium::where('aquariumID', '=', $aquariumID)
      ->first();

    if($aquarium->visibility != 'Public')
      return Redirect::to('/');

    $files = AquariumFile::where('userID', '=', $aquarium->userID)
      ->where('aquariumID', '=', $aquariumID)
      ->orderBy('createdAt', 'desc')
      ->get();
    return View::make('aquariums/files/index')
      ->with('files', $files)
      ->with('aquariumID', $aquariumID);
  }
}
