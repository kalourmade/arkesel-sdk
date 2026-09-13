# Roadmap

v1 (current) covers SMS (send/get/messageReports/sendToGroup),
Contact Groups, Balance, and OTP (generate/verify), across all three
SDKs (PHP, Python, Node), released together from a single git tag.

## v2 — Voice SMS + "click to record"

- `client.voice.send()` across all three SDKs, wrapping
  `POST /api/v2/sms/voice/send`. This endpoint takes an uploaded audio
  file (`voice_file`, multipart binary) rather than text-to-speech, so
  it requires extending all three SDKs' HTTP transports beyond the
  JSON-only design used for v1 — a real transport-layer change, not
  just a new resource class.
- A browser "click to record" demo: a small recording UI (MediaRecorder
  API, record button) that captures audio and uploads it as the
  `voice_file` for a send request. This is example/demo code, not SDK
  code — the SDKs themselves only need to accept and forward the
  uploaded file.
- A voice-specific delivery-callback parser: Voice's callback shape is
  `campaign_id`, `recipient`, `status` (`ANSWERED|NO ANSWER|BUSY|
  SUBMITTED`) — different from SMS's `sms_id`/`status` pair, so it's a
  separate parser, not a reuse of `parseDeliveryCallback`.

Full design (exact multipart implementation per language, whether the
demo ships as its own small app or lives in `examples/`, retry/timeout
behavior for large file uploads) deferred to its own brainstorming/spec
pass when this is picked up.

## Not planned

Arkesel's API surface goes far beyond SMS/Voice/OTP — USSD, Survey
Campaigns, Customer Ticketing, WhatsApp Business, and IVR Apps are all
present in their OpenAPI spec but outside scope here. Revisit only if
there's a concrete need; each of these is roughly its own product with
its own auth scheme (IVR Apps use OAuth bearer tokens, not the
`api-key` header the rest of the API uses) and would warrant a
separate spec, not a bolt-on to this SDK.
