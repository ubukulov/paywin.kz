<div class="hbalance__balance">
    <div class="hbalance__balance-block">
        <div class="hbalance__sum">{{ Auth::user()->getBalanceForUser() }} ₸</div>
{{--        <div class="hbalance__bonus">+ <span>536</span> бонусов</div>--}}
    </div>
    <div class="hbalance__balance-block">
{{--        <a href="#" style="height: 30px !important;" class="hbalance__replenish-btn">+ пополнить</a>--}}
        <button type="button" class="hbalance__replenish-btn" data-toggle="modal" data-target="#exampleModal">+ пополнить</button>
{{--        <a href="#" style="height: 30px !important;" class="hbalance__output-btn">- вывести</a>--}}
    </div>
</div>
<div class="hbalance__wrapper">

    @foreach(Auth::user()->getUserBalances as $balance)
    <div class="hbalance__inner">
        <div class="hbalance__item">
            <div class="hbalance__item-name">{{ $balance->updated_at->format('d.m.Y H:i:s') }}</div>
            <div class="hbalance__item-status">
                @if($balance->status == 'ok')
                    пополнил
                @elseif($balance->status == 'withdraw')
                    вывел
                @endif
            </div>
            <div class="hbalance__item-sum">{{ $balance->amount }} тг</div>
        </div>
    </div>
    @endforeach

</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('user.balanceReplenishment') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Пополнение</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <input type="text" name="amount" placeholder="Введите сумму" required class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Продолжить</button>
                </div>
            </form>
        </div>
    </div>
</div>
