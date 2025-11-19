<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Personal;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class ProfileController extends Controller
{
	public function updatePhoto(Request $request)
	{
		$this->validate($request, [
			'photo' => 'required|image'
		]);

		$file = $request->file('photo');
		$extension = $file->getClientOriginalExtension();
		$fileName = auth()->id() . '.' . $extension;
		$path = public_path('images/usuario/'.$fileName);

			Image::make($file)->fit(144, 144)->save($path);

		$user = auth()->user();
		$user->photo_extension = $extension;
		$saved = $user->save();

		$data['success'] = $saved;
		$data['path'] = $user->getAvatarUrl() . '?' . uniqid();

		return $data;
	}


    public function getimagen(Request $request)
	{
		$user = auth()->user();

		$empresa = Empresa::findOrFail($user->EMP_Id);
		if($empresa->logo_url == ""){
			$ruta = '/storage/archivos/empresa/usuario.png?' . uniqid();
		}else{
			$ruta = '/storage/archivos/empresa/'.$empresa->logo_url.'?'. uniqid();
		}

		return response()->json(['ruta'=>$ruta]);
	}



}
