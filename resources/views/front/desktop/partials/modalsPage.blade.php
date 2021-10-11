<div class="modals modalsRight">
    <div class="modal fade" id="terms">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modalContent">
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="closeModal" data-dismiss="modal"></div>
                    <div class="modalTitle"></div>
                    <div class="modalBody">
                        <div class="col-12 editorPage">
                            @php $page = getPage('terms', $lang->id); @endphp
                            @if (!is_null($page))
                                {!! $page->body !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="shipping">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modalContent">
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="closeModal" data-dismiss="modal"></div>
                    <div class="modalTitle"></div>
                    <div class="modalBody">
                        <div class="col-12 editorPage">
                            @php $page = getPage('livrare-achitare-retur', $lang->id); @endphp
                            @if (!is_null($page))
                                {!! $page->body !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="returns">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modalContent">
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="closeModal" data-dismiss="modal"></div>
                    <div class="modalTitle"></div>
                    <div class="modalBody">
                        <div class="col-12 editorPage">
                            @php $page = getPage('refund', $lang->id); @endphp
                            @if (!is_null($page))
                                {!! $page->body !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalShipping">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modalContent">
                    <div class="closeModal" data-dismiss="modal">
                        <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                    </div>
                    <div class="closeModal" data-dismiss="modal"></div>
                    <div class="modalTitle">
                        <span>Shipping, payment and returns</span>
                    </div>
                    <div class="modalBody">
                        <div class="col-12 editorPage">
                            @php $page = getPage('livrare-achitare-retur', $lang->id); @endphp
                            @if (!is_null($page))
                                {!! $page->body !!}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="modal fade" id="modalSize">
        <div class="modal-dialog">
            <div class="modal-content">
             <div class="modalContent">
                 <div class="closeModal" data-dismiss="modal">
                     <img src="/fronts/img/icons/plusIconBlack.svg" alt="" />
                 </div>
                 <div class="closeModal" data-dismiss="modal"></div>
                   <div class="modalTitle">
                     <span>Size Guide</span>
                   </div>
                   <div class="modalBody">
                       <div class="col-12 editorPage">
                           @php $page = getPage('size-guide', $lang->id); @endphp
                           @if (!is_null($page))
                               {!! $page->body !!}
                           @endif
                       </div>
                   </div>
             </div>
            </div>
        </div>
    </div> --}}

</div>
