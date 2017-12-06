<?

namespace App\Acme\Traint;

trait AdminAction {
    public function before($user, $ability)
    {
        return $user->isAdmin() ;
    }
}
