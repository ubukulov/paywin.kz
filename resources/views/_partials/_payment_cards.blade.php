<div class="action__flex">
    <img src="/b5/img/logotypes/mastercard.svg" alt="mastercard" class="action__icon action__icon--card">

    <select name="card_id" style="margin-left: 8px;
    border-color: #ccc;
    border-radius: 5px;
    color: green;">
        @foreach($user->getMyCards() as $card)
            <option value="{{ $card['id'] }}">{{ $card['number'] }}</option>
        @endforeach
    </select>
</div>
