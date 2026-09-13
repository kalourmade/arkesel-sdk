from kalourmade_arkesel.models import (
    Balance,
    DeliveryStatus,
    OtpGenerateResult,
    OtpVerifyResult,
    ReportEntry,
    SendResponse,
    SmsDetails,
)


def test_sms_details_from_dict_maps_uppercase_id_field():
    details = SmsDetails.from_dict({
        "ID": "f3be70c1-3545-4677-b607-6b5f32202652",
        "status": "DELIVERED",
        "sender": "Arkesel",
        "recipient": "233544919953",
        "message": "Welcome to version 2 of our API!",
        "message_count": 1,
        "sent_at_time": "2021-04-09 18:44:05",
    })

    assert details.id == "f3be70c1-3545-4677-b607-6b5f32202652"
    assert details.status == DeliveryStatus.DELIVERED
    assert details.message_count == 1


def test_send_response_separates_results_and_invalid_numbers():
    response = SendResponse.from_dict({
        "status": "success",
        "data": [
            {"recipient": "233544919953", "id": "9b752841-7ee7-4d40-b4fe-768bfb1da4f0"},
            {"recipient": "233544919953", "id": "7ea01acd-485c-4df3-b646-e9e24430e145"},
            {"invalid numbers": ["22354674948"]},
        ],
    })

    assert len(response.results) == 2
    assert response.results[0].id == "9b752841-7ee7-4d40-b4fe-768bfb1da4f0"
    assert response.invalid_numbers == ["22354674948"]
    assert response.message is None


def test_send_response_handles_scheduled_send_with_no_data_key():
    response = SendResponse.from_dict({"status": "success", "message": "SMS request sent successfully!"})

    assert response.results == []
    assert response.invalid_numbers == []
    assert response.message == "SMS request sent successfully!"


def test_report_entry_from_dict_maps_successful_report():
    entry = ReportEntry.from_dict({
        "sender": "Arkesel",
        "recipient": "233540000000",
        "status": "DELIVERED",
        "message": "Welcome to version 2 of our API!",
        "message_count": 1,
        "sent_at_time": "2021-04-09 18:44:05",
    })

    assert entry.status == DeliveryStatus.DELIVERED
    assert entry.error is None


def test_report_entry_from_dict_maps_error_stub():
    entry = ReportEntry.from_dict({"status": "error", "response": "message does not exist"})

    assert entry.status is None
    assert entry.error == "message does not exist"


def test_balance_from_dict_maps_fields():
    balance = Balance.from_dict({"sms_balance": "2003", "main_balance": "GHS 20.99"})

    assert balance.sms_balance == "2003"
    assert balance.main_balance == "GHS 20.99"


def test_otp_generate_result_from_dict_handles_optional_ussd_code():
    with_ussd = OtpGenerateResult.from_dict({"code": "1000", "ussd_code": "*928*01#", "message": "Successful"})
    without_ussd = OtpGenerateResult.from_dict({"code": "1000", "message": "Successful"})

    assert with_ussd.ussd_code == "*928*01#"
    assert without_ussd.ussd_code is None


def test_otp_verify_result_from_dict():
    result = OtpVerifyResult.from_dict({"code": "1100", "message": "Successful"})

    assert result.code == "1100"
    assert result.message == "Successful"
