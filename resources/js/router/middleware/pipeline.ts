// @ts-ignore
export default function middlewarePipeline (context: any, middleware: any[], index = 0) {
  const nextMiddleware = middleware[index];

  if (!nextMiddleware) {
    return true;
  }

  const redirect = nextMiddleware(context);

  if (typeof redirect === 'object') {
    return redirect;
  }

  return middlewarePipeline(context, middleware, index + 1);
}
