<?php

namespace App\Http\Resources\V1\User;

use Illuminate\Http\Resources\Json\ResourceCollection;
use function jdate;

class UserCollection extends ResourceCollection
{

    protected $pagination;
    public function __construct(mixed $resource, $pagination = true)
    {
        parent::__construct($resource);
        $this->pagination = $pagination;

    }


    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function ($item) {
                if ($this->pagination){
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'slug' => $item->slug,
                        'email' => $item->email,
                        'phone' => $item->phone,
//                        'register_type' => $item->register_type,
//                        'roles' => $item->role()->pluck('name'),
//                        'national_code' => $item->national_code,
                        'gender' => $item->gender,
//                        'fathers_name' => $item->fathers_name,
//                        'birth_certificate_city' => $item->birth_certificate_city,
//                        'birth_date' => $item->birth_date,
                        'province' => $item->province,
                        'city' => $item->city,

//                    'courses' => new CourseCollection($item->courses()->get() , false),
//                    'classrooms' => new ClassroomCollection($item->classrooms()->get() , false),
                        'created_at' => jdate($item->created_at)->format('Y-m-d H:i:s'),

                    ];
                }else{
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'phone' => $item->phone,
                    ];
                }

            }),

        ];
    }
}
