// #region Global Imports
import { WithTranslation } from "next-i18next";
// #endregion Global Imports

declare namespace IOldPromos {
    export interface IProps extends WithTranslation {}

    export interface IState {}

    export interface IStateProps {}

    module Actions {
        export interface IMapPayload {}
        export interface IMapResponse {}
    }
}

export { IOldPromos };
