<!-- Delete confirm -->
<div class="modal-delete js-ak-modal js-ak-modal-delete">
    <div class="modal-center">
        <div class="modal-container">
            <form method="post" class="js-ak-modal-form" action="{{$delete_route??""}}">
                @method('DELETE')
                @csrf
                <div class="modal-content">
                    <div class="modal-dialog">
                        <svg class="info-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>Are you sure you want to delete?</p>
                        <div class="modal-action">
                            <button type="submit" class="button danger-button">
                                @includeIf("layouts.icons.delete_icon")
                                Delete
                            </button>
                            <button type="button" class="button cancel-button js-ak-modal-close">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
                <div class="js-ak-modal-collect-data"></div>
            </form>
        </div>
    </div>
</div>
