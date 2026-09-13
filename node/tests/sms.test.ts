import { describe, expect, it } from 'vitest';
import { SmsResource } from '../src/sms';
import { InsufficientBalanceError } from '../src/errors';
import { FakeTransport } from './fakes';

describe('SmsResource', () => {
  it('send posts to /api/v2/sms/send and returns a parsed response', async () => {
    const body = JSON.stringify({
      status: 'success',
      data: [{ recipient: '233544919953', id: 'abc-123' }],
    });
    const transport = new FakeTransport([{ status: 200, body }]);
    const resource = new SmsResource(transport);

    const response = await resource.send({ sender: 'Arkesel', recipients: ['233544919953'], message: 'Hello world' });

    expect(response.results).toHaveLength(1);
    expect(transport.requests[0].method).toBe('POST');
    expect(transport.requests[0].path).toBe('/api/v2/sms/send');
    expect(transport.requests[0].jsonBody).toEqual({
      sender: 'Arkesel',
      recipients: ['233544919953'],
      message: 'Hello world',
    });
  });

  it('send includes optional fields when provided', async () => {
    const body = JSON.stringify({ status: 'success', data: [] });
    const transport = new FakeTransport([{ status: 200, body }]);
    const resource = new SmsResource(transport);

    await resource.send({
      sender: 'Arkesel',
      recipients: ['233544919953'],
      message: 'Hello',
      callbackUrl: 'https://example.com/cb',
      scheduledDate: '2026-09-12 10:00 AM',
      useCase: 'promotional',
      sandbox: true,
    });

    expect(transport.requests[0].jsonBody).toEqual({
      sender: 'Arkesel',
      recipients: ['233544919953'],
      message: 'Hello',
      callback_url: 'https://example.com/cb',
      scheduled_date: '2026-09-12 10:00 AM',
      use_case: 'promotional',
      sandbox: true,
    });
  });

  it('get fetches SMS details by id', async () => {
    const body = JSON.stringify({
      status: 'success',
      data: {
        ID: 'abc-123',
        status: 'DELIVERED',
        sender: 'Arkesel',
        recipient: '233544919953',
        message: 'Hello',
        message_count: 1,
        sent_at_time: '2021-04-09 18:44:05',
      },
    });
    const transport = new FakeTransport([{ status: 200, body }]);
    const resource = new SmsResource(transport);

    const details = await resource.get('abc-123');

    expect(details.id).toBe('abc-123');
    expect(transport.requests[0].path).toBe('/api/v2/sms/abc-123');
  });

  it('messageReports posts ids and returns a keyed record', async () => {
    const body = JSON.stringify({
      status: 'success',
      data: {
        'abc-123': {
          sender: 'Arkesel',
          recipient: '233540000000',
          status: 'DELIVERED',
          message: 'Hello',
          message_count: 1,
          sent_at_time: '2021-04-09 18:44:05',
        },
        'def-456': { status: 'error', response: 'message does not exist' },
      },
    });
    const transport = new FakeTransport([{ status: 200, body }]);
    const resource = new SmsResource(transport);

    const reports = await resource.messageReports(['abc-123', 'def-456']);

    expect(transport.requests[0].path).toBe('/api/v2/sms/message-reports');
    expect(transport.requests[0].jsonBody).toEqual({ msg_ids: ['abc-123', 'def-456'] });
    expect(reports['abc-123'].error).toBeNull();
    expect(reports['def-456'].error).toBe('message does not exist');
  });

  it('sendToGroup posts to the contact-group endpoint', async () => {
    const body = JSON.stringify({ status: 'success', message: 'SMS request sent successfully!' });
    const transport = new FakeTransport([{ status: 200, body }]);
    const resource = new SmsResource(transport);

    await resource.sendToGroup({ sender: 'Arkesel', groupName: 'New Customers', message: 'Hello group' });

    expect(transport.requests[0].path).toBe('/api/v2/sms/send/contact-group');
    expect(transport.requests[0].jsonBody).toEqual({
      sender: 'Arkesel',
      group_name: 'New Customers',
      message: 'Hello group',
    });
  });

  it('raises the mapped exception for error statuses', async () => {
    const transport = new FakeTransport([{ status: 402, body: '{}' }]);
    const resource = new SmsResource(transport);

    await expect(
      resource.send({ sender: 'Arkesel', recipients: ['233544919953'], message: 'Hello' })
    ).rejects.toBeInstanceOf(InsufficientBalanceError);
  });
});
