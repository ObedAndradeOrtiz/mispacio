<div>
    
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
    <style>
        #Smallchat .Messages,
        #Smallchat .Messages_list {
            -webkit-box-flex: 1;
            -webkit-flex-grow: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
        }

        .chat_close_icon {
            cursor: pointer;
            color: #fff;
            font-size: 16px;
            position: absolute;
            right: 12px;
            z-index: 9;
        }

        .chat_on {
            position: fixed;
            z-index: 10;
            width: 45px;
            height: 45px;
            right: 15px;
            bottom: 20px;
            background-color:  #486de6;
            color: #fff;
            padding-left: 10px;
            padding-top: 15px;
            border-radius: 50%;
            text-align: center;
            box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.16), 0 2px 10px 0 rgba(0, 0, 0, 0.12) !important;
            cursor: pointer;
            display: flex;
        }

        .chat_on_icon {
            color: #fff;
            font-size: 25px;
            text-align: center;
        }

        #Smallchat .Layout {
            pointer-events: auto;
            box-sizing: content-box !important;
            z-index: 999999999;
            position: fixed;
            bottom: 20px;
            min-width: 50px;
            max-width: 300px;
            max-height: 30px;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-pack: end;
            -webkit-justify-content: flex-end;
            -ms-flex-pack: end;
            justify-content: flex-end;
            border-radius: 50px;
            box-shadow: 5px 0 20px 5px rgba(0, 0, 0, .1);
            -webkit-animation: appear .15s cubic-bezier(.25, .25, .5, 1.1);
            animation: appear .15s cubic-bezier(.25, .25, .5, 1.1);
            -webkit-animation-fill-mode: forwards;
            animation-fill-mode: forwards;
            opacity: 0;
            -webkit-transition: right .1s cubic-bezier(.25, .25, .5, 1), bottom .1s cubic-bezier(.25, .25, .5, 1), min-width .2s cubic-bezier(.25, .25, .5, 1), max-width .2s cubic-bezier(.25, .25, .5, 1), min-height .2s cubic-bezier(.25, .25, .5, 1), max-height .2s cubic-bezier(.25, .25, .5, 1), border-radius 50ms cubic-bezier(.25, .25, .5, 1) .15s, background-color 50ms cubic-bezier(.25, .25, .5, 1) .15s, color 50ms cubic-bezier(.25, .25, .5, 1) .15s;
            transition: right .1s cubic-bezier(.25, .25, .5, 1), bottom .1s cubic-bezier(.25, .25, .5, 1), min-width .2s cubic-bezier(.25, .25, .5, 1), max-width .2s cubic-bezier(.25, .25, .5, 1), min-height .2s cubic-bezier(.25, .25, .5, 1), max-height .2s cubic-bezier(.25, .25, .5, 1), border-radius 50ms cubic-bezier(.25, .25, .5, 1) .15s, background-color 50ms cubic-bezier(.25, .25, .5, 1) .15s, color 50ms cubic-bezier(.25, .25, .5, 1) .15s
        }

        #Smallchat .Layout-right {
            right: 20px
        }

        #Smallchat .Layout-open {
            overflow: hidden;
            min-width: 300px;
            max-width: 300px;
            height: 500px;
            max-height: 500px;
            border-radius: 10px;
            color: #fff;
            -webkit-transition: right .1s cubic-bezier(.25, .25, .5, 1), bottom .1s cubic-bezier(.25, .25, .5, 1.1), min-width .2s cubic-bezier(.25, .25, .5, 1.1), max-width .2s cubic-bezier(.25, .25, .5, 1.1), max-height .2s cubic-bezier(.25, .25, .5, 1.1), min-height .2s cubic-bezier(.25, .25, .5, 1.1), border-radius 0ms cubic-bezier(.25, .25, .5, 1.1), background-color 0ms cubic-bezier(.25, .25, .5, 1.1), color 0ms cubic-bezier(.25, .25, .5, 1.1);
            transition: right .1s cubic-bezier(.25, .25, .5, 1), bottom .1s cubic-bezier(.25, .25, .5, 1.1), min-width .2s cubic-bezier(.25, .25, .5, 1.1), max-width .2s cubic-bezier(.25, .25, .5, 1.1), max-height .2s cubic-bezier(.25, .25, .5, 1.1), min-height .2s cubic-bezier(.25, .25, .5, 1.1), border-radius 0ms cubic-bezier(.25, .25, .5, 1.1), background-color 0ms cubic-bezier(.25, .25, .5, 1.1), color 0ms cubic-bezier(.25, .25, .5, 1.1);
        }

        #Smallchat .Layout-expand {
            height: 500px;
            min-height: 500px;
            display: none;
        }

        #Smallchat .Layout-mobile {
            bottom: 10px
        }

        #Smallchat .Layout-mobile.Layout-open {
            width: calc(100% - 20px);
            min-width: calc(100% - 20px)
        }

        #Smallchat .Layout-mobile.Layout-expand {
            bottom: 0;
            height: 100%;
            min-height: 100%;
            width: 100%;
            min-width: 100%;
            border-radius: 0 !important
        }

        @-webkit-keyframes appear {
            0% {
                opacity: 0;
                -webkit-transform: scale(0);
                transform: scale(0)
            }

            to {
                opacity: 1;
                -webkit-transform: scale(1);
                transform: scale(1)
            }
        }

        @keyframes appear {
            0% {
                opacity: 0;
                -webkit-transform: scale(0);
                transform: scale(0)
            }

            to {
                opacity: 1;
                -webkit-transform: scale(1);
                transform: scale(1)
            }
        }

        #Smallchat .Messenger_messenger {
            position: relative;
            height: 100%;
            width: 100%;
            min-width: 300px;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column
        }

        #Smallchat .Messenger_header,
        #Smallchat .Messenger_messenger {
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex
        }

        #Smallchat .Messenger_header {
            -webkit-box-align: center;
            -webkit-align-items: center;
            -ms-flex-align: center;
            align-items: center;
            padding-left: 10px;
            padding-right: 40px;
            height: 40px;
            -webkit-flex-shrink: 0;
            -ms-flex-negative: 0;
            flex-shrink: 0
        }


        #Smallchat .Messenger_header h4 {
            opacity: 0;
            font-size: 16px;
            -webkit-animation: slidein .15s .3s;
            animation: slidein .15s .3s;
            -webkit-animation-fill-mode: forwards;
            animation-fill-mode: forwards
        }

        #Smallchat .Messenger_prompt {
            margin: 0;
            font-size: 16px;
            line-height: 18px;
            font-weight: 400;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis
        }

        @-webkit-keyframes slidein {
            0% {
                opacity: 0;
                -webkit-transform: translateX(10px);
                transform: translateX(10px)
            }

            to {
                opacity: 1;
                -webkit-transform: translateX(0);
                transform: translateX(0)
            }
        }

        @keyframes slidein {
            0% {
                opacity: 0;
                -webkit-transform: translateX(10px);
                transform: translateX(10px)
            }

            to {
                opacity: 1;
                -webkit-transform: translateX(0);
                transform: translateX(0)
            }
        }

        #Smallchat .Messenger_content {
            height: 450px;
            -webkit-box-flex: 1;
            -webkit-flex-grow: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            background-color: #fff;
        }

        #Smallchat .Messages {
            position: relative;
            -webkit-flex-shrink: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
            display: -webkit-box;
            display: -webkit-flex;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -webkit-flex-direction: column;
            -ms-flex-direction: column;
            flex-direction: column;
            overflow-x: hidden;
            overflow-y: auto;
            padding: 10px;
            background-color: #fff;
            -webkit-overflow-scrolling: touch;
        }





        #Smallchat .Input {
            position: relative;
            width: 100%;
            -webkit-box-flex: 0;
            -webkit-flex-grow: 0;
            -ms-flex-positive: 0;
            flex-grow: 0;
            -webkit-flex-shrink: 0;
            -ms-flex-negative: 0;
            flex-shrink: 0;
            padding-top: 17px;
            padding-bottom: 15px;
            color: #96aab4;
            background-color: #fff;

        }

        #Smallchat .Input-blank .Input_field {
            max-height: 20px;
        }

        #Smallchat .Input_field {
            width: 100%;
            resize: none;
            border: none;
            outline: none;
            padding: 0;
            padding-right: 0px;
            padding-left: 0px;
            padding-left: 20px;
            padding-right: 75px;
            background-color: transparent;
            font-size: 16px;
            line-height: 20px;
            min-height: 20px !important;
        }

        #Smallchat .Input_button-emoji {
            right: 45px;
        }

        #Smallchat .Input_button {
            position: absolute;
            bottom: 15px;
            width: 25px;
            height: 25px;
            padding: 0;
            border: none;
            outline: none;
            background-color: transparent;
            cursor: pointer;
        }

        #Smallchat .Input_button-send {
            right: 15px;
        }

        #Smallchat .Input-emoji .Input_button-emoji .Icon,
        #Smallchat .Input_button:hover .Icon {
            -webkit-transform: scale(1.1);
            -ms-transform: scale(1.1);
            transform: scale(1.1);
            -webkit-transition: all .1s ease-in-out;
            transition: all .1s ease-in-out;
        }

        #Smallchat .Input-emoji .Input_button-emoji .Icon path,
        #Smallchat .Input_button:hover .Icon path {
            fill: #2c2c46;
        }
    </style>
    <style>
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            width: 5px;
            background: #f5f5f5;
        }

        ::-webkit-scrollbar-thumb {
            width: 1em;
            background-color: #ddd;
            outline: 1px solid slategrey;
            border-radius: 1rem;
        }

        .text-small {
            font-size: 0.9rem;
        }

        .messages-box,
        .chat-box {
            height: 750px;
            overflow-y: scroll;
            scroll-behavior: smooth;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        input::placeholder {
            font-size: 0.9rem;
            color: #999;
        }

        .circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ccc;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
        }

        .circle-pendiente {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background-color: #486de6;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
    <div class="container">
        <div class="row">
            <div id="Smallchat">
                <div class="Layout Layout-open Layout-expand Layout-right"
                    style="background-color: #3F51B5;color: rgb(255, 255, 255);opacity: 5;border-radius: 10px;">
                    @livewire('mensajeria.chat-interno')
                </div>
                <!--===============CHAT ON BUTTON STRART===============-->
                <div class="chat_on"> @livewire('mensajeria.chat-interno-cantidad') <span class="chat_on_icon">
                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M21.4274 2.5783C20.9274 2.0673 20.1874 1.8783 19.4974 2.0783L3.40742 6.7273C2.67942 6.9293 2.16342 7.5063 2.02442 8.2383C1.88242 8.9843 2.37842 9.9323 3.02642 10.3283L8.05742 13.4003C8.57342 13.7163 9.23942 13.6373 9.66642 13.2093L15.4274 7.4483C15.7174 7.1473 16.1974 7.1473 16.4874 7.4483C16.7774 7.7373 16.7774 8.2083 16.4874 8.5083L10.7164 14.2693C10.2884 14.6973 10.2084 15.3613 10.5234 15.8783L13.5974 20.9283C13.9574 21.5273 14.5774 21.8683 15.2574 21.8683C15.3374 21.8683 15.4274 21.8683 15.5074 21.8573C16.2874 21.7583 16.9074 21.2273 17.1374 20.4773L21.9074 4.5083C22.1174 3.8283 21.9274 3.0883 21.4274 2.5783Z"
                                fill="currentColor"></path>
                            <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                                d="M3.01049 16.8079C2.81849 16.8079 2.62649 16.7349 2.48049 16.5879C2.18749 16.2949 2.18749 15.8209 2.48049 15.5279L3.84549 14.1619C4.13849 13.8699 4.61349 13.8699 4.90649 14.1619C5.19849 14.4549 5.19849 14.9299 4.90649 15.2229L3.54049 16.5879C3.39449 16.7349 3.20249 16.8079 3.01049 16.8079ZM6.77169 18.0003C6.57969 18.0003 6.38769 17.9273 6.24169 17.7803C5.94869 17.4873 5.94869 17.0133 6.24169 16.7203L7.60669 15.3543C7.89969 15.0623 8.37469 15.0623 8.66769 15.3543C8.95969 15.6473 8.95969 16.1223 8.66769 16.4153L7.30169 17.7803C7.15569 17.9273 6.96369 18.0003 6.77169 18.0003ZM7.02539 21.5683C7.17139 21.7153 7.36339 21.7883 7.55539 21.7883C7.74739 21.7883 7.93939 21.7153 8.08539 21.5683L9.45139 20.2033C9.74339 19.9103 9.74339 19.4353 9.45139 19.1423C9.15839 18.8503 8.68339 18.8503 8.39039 19.1423L7.02539 20.5083C6.73239 20.8013 6.73239 21.2753 7.02539 21.5683Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                </div>
                <!--===============CHAT ON BUTTON END===============-->
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $(".chat_on").click(function() {
                $(".Layout").toggle();
                $(".chat_on").hide(300);
            });

            $(".chat_close_icon").click(function() {
                $(".Layout").hide();
                $(".chat_on").show(300);
            });

        });
    </script>
    
</div>
