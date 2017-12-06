<?

namespace App\Acme\Traint;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait ApiResponser {
    private function successRespond($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorRespond($message, $code)
    {
        return response()->json(['message'=>$message, 'code'=>$code], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successRespond($collection, $code);
        }
        $transformer = $collection->first()->transformer;
        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sortData($collection, $transformer);
        $collection = $this->paginate($collection);
        $collection = $this->transformData($collection, $transformer);
        $collection = $this->cacheResponse($collection);

        return $this->successRespond($collection, $code);
    }

    public function showOne(Model $model, $code = 200)
    {

        $transformer = $model->transformer;
        $model = $this->transformData($model, $transformer);
        $model = $this->cacheResponse($model);
        return $this->successRespond($model, $code);
    }

    public function showMessage($message, $code = 200)
    {
        return $this->successRespond(['message'=>$message], $code);
    }

    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, $transformer);
        return $transformation->toArray();
    }

    protected function sortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by')) {
            return $collection->sortBy->{$transformer::originalAttribute(request()->sort_by)};
        }
        return $collection;
    }

    protected function filterData(Collection $collection, $transformer)
    {
        foreach (request()->query() as $query => $value) {
            if (in_array($query, ['sort_by', 'per_page', 'page'])) {
                continue;
            }

            $attribute = $transformer::originalAttribute($query);
            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }

        return $collection;
    }

    protected function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];

        Validator::validate(request()->all(), $rules);
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 15;
        if (request()->has('per_page')) {
            $perPage = (int)request()->per_page;
        }

        $results = $collection->slice(($page - 1)* $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);

        $paginated->appends(request()->all());
        return $paginated;
    }

    protected function cacheResponse($data)
    {
        $url = request()->url();
        $queryParames = request()->query();
        ksort($queryParames);
        $queryString = http_build_query($queryParames);
        $fullUrl = "{$url}?{$queryString}";

        return Cache::remember($fullUrl, 1, function () use ($data) {
            return $data;
        });
    }
}
