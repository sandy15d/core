<div class="form-footer">
    <div class="form-buttons-container">
        <div>
            <button type="submit" class="button primary-button submit-button js-ak-submit-button">
                <span>
                    @includeIf("layouts.loading")
                    Save
                </span>
            </button>
        </div>
        @if($cancel_route)
            <div>
                <a href="{{ $cancel_route }}" class="button cancel-button">Cancel</a>
            </div>
        @endif
    </div>
</div>
