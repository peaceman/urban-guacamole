<?php
namespace DashTec\Middleware;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Route;

class AuthenticationMiddleware extends AbstractFilterableMiddleware
{
    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var Route $currentRoute */
        $currentRoute = $request->getAttribute('route');
        if (!$this->shouldProcessRoute($currentRoute)) {
            return $next($request, $response);
        }

        if (!isset($_SESSION['account_id'])) {
            return $this->redirectToLoginPage($request, $response);
        }

        $account = $this->tryToLoadAccountFromDatabase($_SESSION['account_id']);
        if (!$account) {
            return $response->withRedirect('/logout', 303);
        }

        return $next($request, $response);
    }

    protected function getConfigKey()
    {
        return 'authentication';
    }

    protected function tryToLoadAccountFromDatabase($accountId)
    {
        return ['id' => $accountId, 'username' => 'foobar', 'password' => 'foobar'];
    }

    protected function redirectToLoginPage(Request $request, Response $response)
    {
        $this->storeCurrentUrlInSession($request);
        return $response->withRedirect('/login', 303);
    }

    protected function storeCurrentUrlInSession(Request $request)
    {
        $currentUrl = $request->getUri();
        $_SESSION['authentication.attempted_url'] = $currentUrl;
    }
}

