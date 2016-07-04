interface IObject {
  key: string;
  value: string;
  timestamp?: number;
  datetime?: string;
  logs: IObjectLog[];
}

interface IObjectLog {
  timestamp: number;
  value: string;
}

interface IResult {
  data: any;
  status: number;
}

export class MainController {
  public objects: {[key: string]: IObject};
  public object: IObject;

  public result: IResult;

  /* @ngInject */
  constructor(Restangular: restangular.IService, $rootScope: angular.IRootScopeService) {
    this.restClient = Restangular;
    this.rootScope = $rootScope;
    this.rootScope.$on(this.eventNewObjectSaved, this.getObjects);
    this.getObjects();
  }

  public getObjects = () => {
    var that = this;
    this.restClient.all('/object').getList().then((response: any) => {
      that.objects = <{[key: string]: IObject}>response;
    });
  };

  public getObject = (key: string, timestamp: string) => {
    var that = this;
    var queryParams: any = {};

    if (!key) {
      return;
    }
    this.result = {
      data: '... loading value ...',
      status: 1
    };

    if (parseInt(timestamp, 10) > 0) {
      queryParams.timestamp = timestamp;
    }
    this.restClient.one('/object', key).get(queryParams).then((response: string) => {
      that.result = {
        data: response,
        status: 1
      };
    }, () => {
      that.result = {
        data: 'ERROR: Not Found!',
        status: 0
      };
    });
  };

  public saveObject = (object: IObject) => {
    var data = {};
    var that = this;
    if (typeof object === 'undefined' || !object.key || !object.value) {
      return;
    }
    data[object.key] = object.value;
    this.restClient.all('/object').post(data).then((response: any) => {
      if (response.status) {
        var now = moment().utc();
        object.datetime = now.format();
        object.timestamp = now.unix();
        that.rootScope.$broadcast(that.eventNewObjectSaved, object);
      }
    });
  };

  private restClient: restangular.IService;
  private rootScope: angular.IRootScopeService;
  private eventNewObjectSaved = 'newObjectSaved';
}
