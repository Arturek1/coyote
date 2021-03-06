<?php

namespace Coyote\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Coyote\Repositories\Contracts\TagRepositoryInterface as TagRepository;
use Coyote\Repositories\Contracts\ForumRepositoryInterface as ForumRepository;

class TagController extends BaseController
{
    /**
     * Save user's custom tags
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function save(Request $request)
    {
        $this->validate($request, ['tag' => 'array', 'tags.*' => 'required|max:25|tag']);

        $tags = json_encode($request->get('tags', []));
        $this->setSetting('forum.tags', $tags);

        return view('forum.partials.tags')->with('tags', $this->getUserTags());
    }

    /**
     * @param Request $request
     * @param TagRepository $tag
     * @param ForumRepository $forum
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    public function prompt(Request $request, TagRepository $tag, ForumRepository $forum)
    {
        // we don't wanna tags with "#" at the beginning
        $request->merge(['q' => ltrim($request['q'], '#')]);
        $this->validate($request, ['q' => 'required|string|max:25']);

        // search for tag
        $tags = $tag->lookupName($request->input('q'));
        // calculate weight
        $tags = $forum->getTagsWeight($tags->pluck('name')->toArray());

        return view('components.tags')->with('tags', $tags);
    }

    /**
     * @param Request $request
     */
    public function valid(Request $request)
    {
        // we don't wanna tags with "#" at the beginning
        $request->merge(['t' => ltrim($request['t'], '#')]);

        $this->validate($request, ['t' => 'required|string|max:25|tag|tag_creation:50']);
    }
}
