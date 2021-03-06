<?php namespace GeneaLabs\LaravelGovernor\Http\Controllers;

use GeneaLabs\LaravelGovernor\Entity;
use GeneaLabs\LaravelGovernor\Http\Requests\CreateEntityRequest;
use GeneaLabs\LaravelGovernor\Http\Requests\UpdateEntityRequest;
use Illuminate\Support\Facades\App;

class EntitiesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return mixed
     */
    public function index()
    {
        $this->authorize('view', (new Entity()));
        $entities = Entity::groupBy('name')->get();

        return view('genealabs-laravel-governor::entities.index', compact('entities'));
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $entity = new Entity();
        $this->authorize('create', $entity);

        return view('genealabs-laravel-governor::entities.create', compact('entity'));
    }

    /**
     * @return mixed
     */
    public function store(CreateEntityRequest $request)
    {
        Entity::create($request->only('name'));
        $this->resetSuperAdminPermissions();

        return redirect()->route('genealabs.laravel-governor.entities.index');
    }

    /**
     * @param $name
     * @return mixed
     */
    public function edit($name)
    {
        $entity = Entity::find($name);
        $this->authorize($entity);

        return view('genealabs-laravel-governor::entities.edit', compact('entity'));
    }

    /**
     * @param $name
     * @return mixed
     */
    public function update(UpdateEntityRequest $request, $name)
    {
        $entity = Entity::find($name);
        $entity->fill($request->only('name'));
        $entity->save();
        $this->resetSuperAdminPermissions();

        return redirect()->route('genealabs.laravel-governor.entities.index');
    }

    /**
     * @param $name
     * @return mixed
     */
    public function destroy($name)
    {
        $entity = Entity::find($name);
        $this->authorize('remove', $entity);
        $entity->delete();
        $this->resetSuperAdminPermissions();

        return redirect()->route('genealabs.laravel-governor.entities.index');
    }
}
