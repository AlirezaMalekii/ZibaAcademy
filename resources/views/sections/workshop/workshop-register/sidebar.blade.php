<div class="col-12 col-lg-4 register-left p-4 mt-3 mt-lg-0">
    <div class="register-left-detail text-right">
        <h4>جزییات:</h4>
        <div class="register-left-detail-items">
            <div class="register-left-detail-item d-flex flex-row-reverse">
                <img src="/images/teacher.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    تعداد ثبت نام ها:۱۵ نفر
                </p>
            </div>
            <div class="register-left-detail-item d-flex flex-row-reverse">
                <img src="/images/Warning.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    وضعیت ورکشاپ:در حال ثبت نام
                </p>
            </div>
            <div class="register-left-detail-item d-flex    flex-row-reverse">
                <img src="/images/people1.png" alt="icon" width="24px" height="24px">
                <p class="mr-2">
                    ظرفیت ورکشاپ:{{$workshop_data['capacity']}} نفر
                </p>
            </div>
        </div>
    </div>
    <div class="register-left-detail-buy text-right">
        <h4>
            جمع کل:
        </h4>
        <h5 class="desc-price text-center p-3 mt-3">
            <span dir="ltr">{{$workshop_data['price']}}</span> تومان
        </h5>
        <a href="#" class="ex-bold-button mt-4">
            ادامه جهت تکمیل اطلاعت
        </a>
    </div>
</div>
