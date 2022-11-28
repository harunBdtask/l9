<?php

namespace SkylarkSoft\GoRMG\HR;

use Illuminate\Http\Response;

class ApiResponse
{
    protected $data;

    protected $resourceType;

    public function __construct($data, $resourceType)
    {
        $this->data = $data;
        $this->resourceType = $resourceType;
    }

    public static function create($data, $resourceType)
    {
        return new static($data, $resourceType);
    }

    public function getResponse()
    {
        if ($this->data) {
            return $this->successResponse();
        }

        return $this->errorResponse();
    }

    protected function successResponse()
    {
        if ($this->data instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            return $this->resourceType::collection($this->data);
        }

        if ($this->data instanceof \Illuminate\Database\Eloquent\Collection) {
            return response()->json([
                'success' => true,
                'message' => 'Operation successfull',
                'data' => $this->resourceType::collection($this->data)
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Operation successfull',
            'data' => $this->data === true ? null : new $this->resourceType($this->data),
        ], 200);
    }

    protected function errorResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Something Went Wrong!!!',
            'errors' => true,
        ], 400);
    }
}
