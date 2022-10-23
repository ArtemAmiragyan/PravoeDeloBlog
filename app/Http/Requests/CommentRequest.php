<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'article_id' => ['required', Rule::exists((new Article())->getTable(), 'id')],
            'content' => ['required', 'string', 'max:255'],
        ];
    }

    /** @return string */
    public function getArticleId(): string
    {
        return $this->get('article_id');
    }
}
