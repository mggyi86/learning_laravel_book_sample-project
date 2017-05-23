<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Widget;
use App\Http\AuthTraits\OwnsRecord;
use App\Exceptions\UnauthorizedException;
class WidgetController extends Controller
{
    use OwnsRecord;
    public function __construct()
    {
//        $this->middleware(['auth', 'admin'] , ['except' => ['index','show']]);
        $this->middleware('auth', ['except' => 'index']);
        $this->middleware('admin', ['except' => ['index', 'show']]);
    }


    public function index()
    {
        $widgets = Widget::paginate(10);

        return view('widget.index', compact('widgets'));
    }


    public function create()
    {
        return view('widget.create');
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:widgets|string|max:30'
        ]);
        $slug = str_slug($request->name, "-");
        $widget = Widget::create([  'name' => $request->name,
                                    'slug' => $slug,
                                    'user_id' => Auth::id() ]);

        $widget->save();
        alert()->success('Congrats', 'You made a Widget');

        return Redirect::route('widget.index');
    }


/*    public function show($id, $slug = '')
    {
        $widget = Widget::findOrFail($id);
        if ($widget->slug !== $slug){
            return Redirect::route('widget.show',[
                'id' => $widget->id,
                'slug' => $widget->slug
            ], 301);
        }
        return view('widget.show', compact('widget'));
    }*/

    public function show(Widget $widget, $slug = '')
    {
        if ($widget->slug !== $slug){
            return Redirect::route('widget.show',[
                'id' => $widget->id,
                'slug' => $widget->slug
            ], 301);
        }
        return view('widget.show', compact('widget'));
    }
/*    public function edit($id)
    {
        $widget = Widget::findOrFail($id);

        return view('widget.edit', compact('widget'));
    }*/
    public function edit(Widget $widget)
    {
        if ( ! $this->adminOrCurrentUserOwns($widget))
        {
            throw new UnauthorizedException;
        }
        return view('widget.edit', compact('widget'));
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
           'name' => 'required|string|max:30|unique:widgets,name,' .$id
        ]);

        $widget = Widget::findOrFail($id);

        if( ! $this->adminOrCurrentUserOwns($widget)){
            throw new UnauthorizedException;
        }
        $slug = str_slug($request->name);

        $widget->update(['name'=>$request->name,
                        'slug'=>$slug,
                        'user_id'=>Auth::id() ]);
        alert()->success('Congrats', 'You updated a widget');

        return Redirect::route('widget.show', [ 'widget' => $widget, 'slug' => $slug ]);
    }

/*    public function update(Request $request, Widget $widget)
    {
        $this->validate($request, [
            'name' => 'required|string|max:30|unique:widgets,name,'
            .$widget->id
        ]);

        $slug = str_slug($request->name, "-");

        $widget->update(['name'=>$request->name,
            'slug'=>$slug,
            'user_id'=>Auth::id() ]);
        alert()->success('Congrats', 'You updated a widget');

        return Redirect::route('widget.show', [ 'widget' => $widget, 'slug' => $slug ]);
    }*/

    public function destroy($id)
    {
        $widget = Widget::findOrFail($id);
        if( ! $this->adminOrCurrentUserOwns($widget)){
            throw new UnauthorizedException();
        }
        Widget::destroy($id);
        alert()->overlay('Attenion', 'You deleted a widget', 'error');
        return Redirect::route('widget.index');
    }
}
