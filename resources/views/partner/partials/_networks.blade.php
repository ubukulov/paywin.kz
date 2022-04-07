<div class="form-group">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
                <i class="fa fa-vk" aria-hidden="true"></i>
            </span>
        </div>
        <input type="text" class="form-control" name="vk" value="{{ $user_profile->vk }}" aria-label="Username" aria-describedby="basic-addon1">
    </div>
</div>

<div class="form-group">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
                <i class="fa fa-telegram" aria-hidden="true"></i>
            </span>
        </div>
        <input type="text" class="form-control" name="telegram" value="{{ $user_profile->telegram }}" aria-label="Username" aria-describedby="basic-addon1">
    </div>
</div>

<div class="form-group">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
                <i class="fa fa-instagram" aria-hidden="true"></i>
            </span>
        </div>
        <input type="text" class="form-control" value="{{ $user_profile->instagram }}" name="instagram" aria-label="Username" aria-describedby="basic-addon1">
    </div>
</div>

<div class="form-group">
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">
                <i class="fa fa-facebook" aria-hidden="true"></i>
            </span>
        </div>
        <input type="text" class="form-control" value="{{ $user_profile->facebook }}" name="facebook" aria-label="Username" aria-describedby="basic-addon1">
    </div>
</div>
