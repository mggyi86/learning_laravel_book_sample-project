<?php

namespace App\Http\Controllers;

use App\Traits\ManagesImages;
use App\Http\Requests\CreateImageRequest;
use App\MarketingImage;
use App\Http\Requests\EditImageRequest;
use Illuminate\Http\Request;

class MarketingImageController extends Controller
{
    use ManagesImages;
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
        $this->setImageDefaultsFromConfig('marketingImage');
    }

    public function index()
    {
/*        $thumbnailPath = $this->thumbnailPath;
        $marketingImages = MarketingImage::orderBy('image_weight', 'asc')->paginate(10);
        return view('marketing-image.index', compact('marketingImages', 'thumbnailPath'));*/
        return view('marketing-image.index');
    }


    public function create()
    {
        return view('marketing-image.create');
    }


    public function store(CreateImageRequest $request)
    {
        $marketingImage = new MarketingImage([
            'image_name'        => $request->get('image_name'),
            'image_extension'   => $request->file('image')->getClientOriginalExtension(),
            'image_weight'      =>  $request->get('image_weight'),
            'is_active'         => $request->get('is_active'),
            'is_featured'       => $request->get('is_featured')
        ]);
        $marketingImage->save();
        $file = $this->getUploadedFile();
        $this->saveImageFiles($file, $marketingImage);
        alert()->success('Congrats!', 'Marketing Image And Thumbnail Created!');
        return redirect()->route('marketing-image.show', [$marketingImage]);
    }


    public function show($id)
    {
        $marketingImage = MarketingImage::findOrFail($id);
        $thumbnailPath = $this->thumbnailPath;
        $imagePath = $this->imagePath;
        return view('marketing-image.show', compact('marketingImage', 'thumbnailPath', 'imagePath'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $marketingImage = MarketingImage::findOrFail($id);
        $thumbnailPath = $this->thumbnailPath;
        return view('marketing-image.edit', compact('marketingImage', 'thumbnailPath'));
    }

    public function update($id, EditImageRequest $request)
    {
        $marketingImage = MarketingImage::findOrFail($id);
        $this->setUpdatedModelValues($request, $marketingImage);
        if ($this->newFileIsUploaded()) {
            $this->deleteExistingImages($marketingImage);
            $this->setNewFileExtension($request, $marketingImage);
        }
        $marketingImage->save();
        if ($this->newFileIsUploaded()) {
            $file = $this->getUploadedFile();
            $this->saveImageFiles($file, $marketingImage);
        }
            $thumbnailPath = $this->thumbnailPath;
            $imagePath = $this->imagePath;
            alert()->success('Congrats!', 'image edited!');
            return view('marketing-image.show', compact('marketingImage', 'thumbnailPath', 'imagePath'));

    }

    public function destroy($id)
    {
        $marketingImage = MarketingImage::findOrFail($id);
        $this->deleteExistingImages($marketingImage);
        MarketingImage::destroy($id);
        alert()->error('Notice', 'image deleted!');
        return redirect()->route('marketing-image.index');
    }

    private function setNewFileExtension(EditImageRequest $request, $marketingImage){
        $marketingImage->image_extension = $request->file('image')->getClientOriginalExtension();
    }

    private function setUpdatedModelValues(EditImageRequest $request, $marketingImage){
        $marketingImage->is_active = $request->get('is_active');
        $marketingImage->is_featured = $request->get('is_featured');
        $marketingImage->image_weight = $request->get('image_weight');
    }
}
