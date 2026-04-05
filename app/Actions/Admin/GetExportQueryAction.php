<?php 

namespace App\Actions\Admin;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class GetExportQueryAction
{
    /**
     * აბრუნებს Builder-ს, რათა ექსპორტმა შეძლოს მონაცემების ნაკადად (Stream) წამოღება.
     */
    public function execute(string $modelType, array $filters = []): Builder
    {
        return match ($modelType) {
            'users' => User::query()
                ->with('role')
                ->select(['id', 'name', 'email', 'role_id', 'created_at']),

            'posts' => Post::query()
                ->with([
                    'user:id,name',
                    'translations' => fn($q) => $q->where('locale', $filters['locale'] ?? app()->getLocale())
                ]),

            default => throw new \InvalidArgumentException("Unsupported model type for export"),
        };
    }
}