import { describe, expect, it } from 'vitest';
import { GroupsResource } from '../src/groups';
import { ValidationError } from '../src/errors';
import { FakeTransport } from './fakes';

describe('GroupsResource', () => {
  it('create posts group_name', async () => {
    const body = JSON.stringify({ status: 'success', message: 'Contact group has been created successfully!' });
    const transport = new FakeTransport([{ status: 201, body }]);
    const resource = new GroupsResource(transport);

    await resource.create('New Customers');

    expect(transport.requests[0].path).toBe('/api/v2/contacts/groups');
    expect(transport.requests[0].jsonBody).toEqual({ group_name: 'New Customers' });
  });

  it('addContacts maps camelCase params to snake_case and posts them', async () => {
    const body = JSON.stringify({ status: 'success', message: 'Contacts have been created successfully!' });
    const transport = new FakeTransport([{ status: 201, body }]);
    const resource = new GroupsResource(transport);

    await resource.addContacts('New Customers', [
      { phoneNumber: '233544919953' },
      { phoneNumber: '233544919953', firstName: 'Arkesel', lastName: 'Dev' },
    ]);

    expect(transport.requests[0].path).toBe('/api/v2/contacts');
    expect(transport.requests[0].jsonBody).toEqual({
      group_name: 'New Customers',
      contacts: [
        { phone_number: '233544919953' },
        { phone_number: '233544919953', first_name: 'Arkesel', last_name: 'Dev' },
      ],
    });
  });

  it('raises ValidationError on 422', async () => {
    const transport = new FakeTransport([{ status: 422, body: '{}' }]);
    const resource = new GroupsResource(transport);

    await expect(resource.create('')).rejects.toBeInstanceOf(ValidationError);
  });
});
