<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\MarketingImage;
use App\Traits\ManagesImages;
use App\Traits\ShowsImages;


class PagesController extends Controller
{
    use ManagesImages, ShowsImages;

    public function __construct()
    {
        $this->setImageDefaultsFromConfig('marketingImage');
    }

    public function index()
    {
        $featuredImage = MarketingImage::where('is_featured', 1)
                                        ->where('is_active', 1)
                                        ->first();
        $activeImages = MarketingImage::where('is_featured', 0)
                                        ->where('is_active', 1)
                                        ->orderBy('image_weight', 'asc')
                                        ->get();

        $count = count($activeImages);
        $notEnoughImages = $this->notEnoughSliderImages($featuredImage, $activeImages);
        $imagePath = $this->imagePath;
        return view('pages.index', compact('featuredImage','activeImages', 'count', 'notEnoughImages', 'imagePath'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function terms(){
        return view('pages.terms-of-service');
    }

    public function privacy(){
        return view('pages.privacy');
    }
}
