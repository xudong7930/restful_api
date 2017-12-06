<?

namespace App\Http\Controllers;

use App\Acme\Traint\ApiResponser;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller {
    use ApiResponser;

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    protected function allowedAdminAction()
    {
        if (!Gate::allows('admin-action')) {
            throw new AuthorizationException('This action is unauthorized');
        }
    }

    protected function forbidAdminAction()
    {
        if (!Gate::denies('admin-action')) {
            throw new AuthorizationException('This action is unauthorized');
        }
    }
}
