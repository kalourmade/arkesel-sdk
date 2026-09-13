class FakeTransport:
    def __init__(self, responses):
        self._responses = list(responses)
        self.requests = []

    def request(self, method, path, query=None, json_body=None):
        self.requests.append(
            {"method": method, "path": path, "query": query, "json_body": json_body}
        )
        return self._responses.pop(0)
