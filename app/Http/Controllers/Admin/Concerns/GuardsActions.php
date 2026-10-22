<?php

namespace App\Http\Controllers\Admin\Concerns;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 * One place for "something unexpected failed while saving / deleting":
 * the error goes to storage/logs, and the admin sees a friendly message instead of a crash page.
 * Validation errors and 404s keep their normal behaviour.
 */
trait GuardsActions
{
    /**
     * @param Closure $action      the work; return the redirect for the success case
     * @param string  $failure     what the admin reads when it fails
     * @param bool    $keepInput   keep what was typed in the form
     */
    protected function attempt(Closure $action, $failure, $keepInput = true)
    {
        try {
            return $action();
        } catch (ValidationException $e) {
            throw $e;
        } catch (HttpExceptionInterface $e) {
            throw $e;
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);

            $back = back();
            if ($keepInput) {
                $back = $back->withInput();
            }

            return $back->with('error', $failure);
        }
    }
}
