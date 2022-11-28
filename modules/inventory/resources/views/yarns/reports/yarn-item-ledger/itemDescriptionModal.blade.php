<!-- Modal -->
<div class="modal fade" id="itemDesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Item Description</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-md-4">
                        <label>Yarn Composition</label>
                        <select name="yarn_composition"
                                id="yarnComposition"
                                class="form-control form-control-sm search-field text-center c-select select2-input">
                            <option value="">Select</option>
                            @foreach($yarn_composition as $value)
                                <option
                                    value="{{ $value->id }}">
                                    {{ $value->yarn_composition }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Yarn Count</label>
                        <select name="yarn_count"
                                id="yarnCount"
                                class="form-control form-control-sm search-field text-center c-select select2-input">
                            <option value="">Select</option>
                            @foreach($yarn_count as $value)
                                <option
                                    value="{{ $value->id }}">
                                    {{ $value->yarn_count }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Yarn Type</label>
                        <select name="yarn_type"
                                id="yarnType"
                                class="form-control form-control-sm search-field text-center c-select select2-input">
                            <option value="">Select</option>
                            @foreach($yarn_type as $value)
                                <option
                                    value="{{ $value->id }}">
                                    {{ $value->yarn_type }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" id="saveData">Save</button>
            </div>
        </div>
    </div>
</div>
