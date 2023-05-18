@extends('../layout/login')

@section('head')
    <title>EPS-Genco3 - Tiếp nhận xử lý yêu cầu</title>
@endsection

@section('content')
    <div class="container sm:px-10">
        <div class="block xl:grid grid-cols-2 gap-4">
            <!-- BEGIN: Login Info -->
            <div class="hidden xl:flex flex-col min-h-screen">
                <a href="" class="-intro-x flex items-center pt-5">
                    <img alt="EPS-Genco3" class="w-6" src="{{ asset('dist/images/logo.png') }}">
                    <span class="text-white text-lg ml-3">
                        PES-Genco3
                    </span>
                </a>
                <div class="my-auto">
                    <img alt="EPS-Genco3" class="-intro-x w-1/2 -mt-16" src="{{ asset('dist/images/illustration.svg') }}">
                    <div class="-intro-x text-white font-medium text-4xl leading-tight mt-10">A few more clicks to <br> sign in to your account.</div>
                    <div class="-intro-x mt-5 text-lg text-white text-opacity-70 dark:text-gray-500">Manage all your e-commerce accounts in one place</div>
                </div>
            </div>
            <!-- END: Login Info -->
            <!-- BEGIN: Login Form -->
            <div class="h-screen xl:h-auto flex py-5 xl:py-0 my-10 xl:my-0">
                <div class="my-auto mx-auto xl:ml-20 bg-white dark:bg-dark-1 xl:bg-transparent px-5 sm:px-8 py-8 xl:p-0 rounded-md shadow-md xl:shadow-none w-full sm:w-3/4 lg:w-2/4 xl:w-auto">
                    <h2 class="intro-x font-bold text-2xl xl:text-3xl text-center xl:text-left">Khôi phục mật khẩu</h2>
                    <div class="intro-x mt-2 text-gray-500 xl:hidden text-center">A few more clicks to sign in to your account. Manage all your e-commerce accounts in one place</div>
                    <div class="intro-x mt-8">
                        <form id="forgot-form">
                            <input id="email" name="email" type="email" class="intro-x forgot__input form-control py-3 px-4 border-gray-300 block" placeholder="Email">
                            <div id="error-email" class="forgot_input-error w-5/6 text-theme-6 mt-2"></div>
                        </form>
                    </div>
                    <div class="intro-x mt-5 xl:mt-8 text-center xl:text-left">
                        <button id="btn-forgot" class="btn btn-primary py-3 px-4 w-full xl:w-32 xl:mr-3 align-top">Xác nhận</button>
                    </div>
                </div>
            </div>
            <!-- END: Login Form -->
        </div>
    </div>    
@endsection

@section('script')
    <script>
        cash(function () {
            async function forgot() {
                // Reset state
                cash('#forgot-form').find('.forgot__input').removeClass('border-theme-6')
                cash('#forgot-form').find('.forgot__input-error').html('')

                // Post form
                let email = cash('#email').val();
                
                // Loading state
                cash('#btn-forgot').html('<i data-loading-icon="oval" data-color="white" class="w-5 h-5 mx-auto"></i>').svgLoader()
                await helper.delay(1500)

                axios.post(`forgot-password`, {
                    email: email
                }).then(res => {
                    location.href = '/'
                }).catch(err => {
                    cash('#btn-forgot').html('Xác nhận')
                    if (err.response.data.message) {
                        for (const [key, val] of Object.entries(err.response.data.errors)) {
                            cash(`#${key}`).addClass('border-theme-6')
                            cash(`#error-${key}`).html(val)
                        }
                    }
                })
            }

            cash('#forgot-form').on('keyup', function(e) {
                if (e.keyCode === 13) {
                    forgot()
                }
            })
            
            cash('#btn-forgot').on('click', function() {
                forgot()
            })
        })
    </script>
@endsection