/** @ngInject */
export function runBlock($log: angular.ILogService, $rootScope: angular.IRootScopeService, $cookies: angular.cookies.ICookiesService, Restangular: restangular.IService) {
  $log.debug('runBlock end');

  ($rootScope as any).updateBaseUrl = (baseUrl: string, silent: boolean) => {
    var expires: string = moment().add(1, 'w').utc().format();
    Restangular.setBaseUrl(baseUrl);
    $cookies.put('baseUrl', baseUrl, {expires: expires});
    if (typeof silent === 'undefined') {
      $cookies.put('baseUrlNoticed', '1', {expires: expires});
    }
    ($rootScope as any).showBaseUrlForm = !($rootScope as any).showBaseUrlForm;
  };

  var savedBaseUrl: string = $cookies.get('baseUrl') || 'http://localhost:30101';
  ($rootScope as any).showBaseUrlForm = !$cookies.get('baseUrlNoticed');
  ($rootScope as any).baseUrl = savedBaseUrl;
  ($rootScope as any).updateBaseUrl(savedBaseUrl, true);
}
